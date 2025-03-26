<?php

namespace App\Repository;

use App\Entity\Agent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Agent>
 *
 * @method Agent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agent[]    findAll()
 * @method Agent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agent::class);
    }

    /**
     * Find agents by search term (username or full name).
     */
    public function findBySearch(string $search, int $limit, int $offset): array
    {
        $qb = $this->createQueryBuilder('a');

        if (!empty($search)) {
            $qb->join('App\Entity\User', 'u', 'WITH', 'u.agent = a.id')
               ->where('u.username LIKE :search OR a.fullName LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        $qb->setMaxResults($limit)
           ->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }
}
