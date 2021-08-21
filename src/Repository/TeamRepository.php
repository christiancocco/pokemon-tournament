<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    /**
     * @return Team[] Returns an array of all Team objects
     */
    public function getAllOrderBy(string $field, string $order)
    {
        return $this->findBy(array(), array($field => $order));
    }

    /**
     * @return Team[] Returns an array of all Team objects 
     */
    public function getAll()
    {
        $cache = new FilesystemAdapter();

        // The callable will only be executed on a cache miss.
        $teams = $cache->get('teams', function (ItemInterface $item) {
            $item->expiresAfter(3600);
            $result = $this->findAll();
            return $result;
        });
        //$cache->delete('teams');
        return $teams;
    }


    /**
     * @return Array Return an array of custom objects
     */
    public function getAllTeamsForList(string $field = 'id', string $order = 'ASC')
    {
        $cache = new FilesystemAdapter();
        // The callable will only be executed on a cache miss.
        $teams = $cache->get('teamslist', function (ItemInterface $item) use ($field, $order) {
            $item->expiresAfter(3600);
            $result = $this->getAllOrderBy($field, $order);

            $retValue = [];
            foreach ($result as $team) {
                $totalBaseExperience = 0;
                $items = $team->getItems();
                $pokemons = [];
                $types = [];

                foreach ($items as $pokemon) {
                    $totalBaseExperience += $pokemon['info']['base_experience'];
                    $pokemons[] = [
                        "image" => $pokemon['info']['sprites']['front_default'],
                        "name" => $pokemon['info']['name']
                    ];
                    foreach ($pokemon['info']['types'] as $type) {
                        if (!in_array($type['type']['name'], $types)) {
                            $types[] = $type['type']['name'];
                        }
                    }
                }
                $retValue[] = [
                    "id" => $team->getId(),
                    "name" => $team->getName(),
                    "total_base_experience" => $totalBaseExperience,
                    "pokemons" => $pokemons,
                    "types" => $types,
                    "created" => $team->getCreated()
                ];
            }

            return $retValue;
        });
        $cache->delete('teamslist');
        return $teams;
    }
}
