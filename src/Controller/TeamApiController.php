<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Team;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/team")
 */
class TeamApiController extends AbstractController
{
    /**
     * @Route("/{id}/delete", name="team_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $team = $this->getDoctrine()->getRepository(Team::class)->findOneBy(['id' => $id]);
        $entityManager->remove($team);
        $entityManager->flush();
        return $this->json(['code' => 200, 'message' => 'OK!']);
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
}
