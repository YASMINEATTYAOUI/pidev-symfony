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

    // Add custom query methods here
}
