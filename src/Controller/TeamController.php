<?php

namespace App\Controller;

use App\Repository\TeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use PokePHP\PokeApi;
use Psr\Log\LoggerInterface;
use Rompetomp\InertiaBundle\Service\InertiaInterface;
use App\Entity\Team;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/team")
 */
class TeamController extends AbstractController
{
    /**
     * @Route("/list", name="team_list", methods={"GET","HEAD"})
     */
    public function list(InertiaInterface $inertia, TeamRepository $teamRepository, LoggerInterface $logger)
    {
        $cache = new FilesystemAdapter();

        $teams = $teamRepository->getAllTeamsForList('created', 'DESC');

        $pokemonTypes = $cache->get('pokemon-types', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $api = new PokeApi();
            $all = json_decode($api->resourceList('type', '2000', '0'));
            return $all->results;
        });
        return $inertia->render('Team/List', ['pokemonTypes' => $pokemonTypes , "pokemonTeams" => $teams]);
    }


    /**
     * @Route("/create", name="team_create", methods={"GET","HEAD"})
     */
    public function create(InertiaInterface $inertia, LoggerInterface $logger)
    {
        $cache = new FilesystemAdapter();
        $pokemons = $cache->get('pokemons', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $api = new PokeApi();
            $all = json_decode($api->resourceList('pokemon', '999999999', '0'));   
            return $all;
        });
        $cache->delete('pokemons');
        return $inertia->render('Team/Create', ['data' => $pokemons]);
    }

    /**
     * @Route("/{id}/edit", name="team_edit", methods={"GET","HEAD"})
     */
    public function edit(InertiaInterface $inertia, Team $team, LoggerInterface $logger)
    {

        $cache = new FilesystemAdapter();
        $pokemons = $cache->get('pokemons', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $api = new PokeApi();
            $all = json_decode($api->resourceList('pokemon', '999999999', '0'));
            return $all;
        });
        return $inertia->render('Team/Create', ['data' => $pokemons, 'team' => $team]);
    }

    /**
     * @Route("/save", name="save_team", methods={"POST", "PUT"})
     */
    public function save(Request $request, ValidatorInterface $validator): Response
    {
        $name = json_decode($request->getContent())->name;
        $id = json_decode($request->getContent())->id;
        $pokemons = json_decode($request->getContent())->teamitems;
        $entityManager = $this->getDoctrine()->getManager();
        switch ($request->getMethod()) {
            case 'POST':
                $team = new Team();
                $team->setName($name);
                $team->setItems($pokemons);
                $errors = $validator->validate($team);
                if (count($errors) > 0) {
                    return $this->json(['code' => 400, 'errors' => $errors]);
                }
                // tell Doctrine you want to (eventually) save the Product (no queries yet)
                $entityManager->persist($team);
                // actually executes the queries (i.e. the INSERT query)
                $entityManager->flush();
                $cache = new FilesystemAdapter();
                $cache->delete('teamslist');
                break;
            case 'PUT':
                $team = $entityManager->getRepository(Team::class)->find($id);
                if (!$team) {
                    throw $this->createNotFoundException(
                        'No team found for id ' . $id
                    );
                }
                $team->setName($name);
                $team->setItems($pokemons);
                $errors = $validator->validate($team);
                if (count($errors) > 0) {
                    return $this->json(['code' => 400, 'errors' => $errors]);
                }
                $entityManager->flush();
                $cache = new FilesystemAdapter();
                $cache->delete('teamslist');
                break;
            default:
                return $this->json(['code' => 405, 'message' => 'Method not allowed!']);
                break;
        }
        return $this->json(['code' => 200, 'message' => 'OK!']);
    }

    /**
     * @Route("/api/pokemon/{name}", name="pokemon_info", methods={"GET"})
     */
    public function pokemonapi(string $name): Response
    {
        $cache = new FilesystemAdapter();
        $pokemon = $cache->get('pokemon-' . $name, function (ItemInterface $item) use ($name) {
            $item->expiresAfter(3600);
            $api = new PokeApi();
            $pokemon = json_decode($api->pokemon($name));
            return $pokemon;
        });          
        return $this->json($pokemon);
    }
}
