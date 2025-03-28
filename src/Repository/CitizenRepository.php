<?php

namespace App\Repository;

use App\Entity\Citizen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Citizen>
 *
 * @method Citizen|null find($id, $lockMode = null, $lockVersion = null)
 * @method Citizen|null findOneBy(array $criteria, array $orderBy = null)
 * @method Citizen[]    findAll()
 * @method Citizen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CitizenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Citizen::class);
    }

    public function findBySearch(string $search, int $limit, int $offset): array
    {
        $qb = $this->createQueryBuilder('c');

        if (!empty($search)) {
            $qb->join('App\Entity\User', 'u', 'WITH', 'u.citizen = c.id')
               ->where('u.username LIKE :search OR c.fullName LIKE :search OR c.email LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        $qb->setMaxResults($limit)
           ->setFirstResult($offset);

        return $qb->getQuery()->getResult();
    }
}
