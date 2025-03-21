<?php

namespace App\Repository;

use App\Entity\EventCitizen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventCitizen>
 *
 * @method EventCitizen|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventCitizen|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventCitizen[]    findAll()
 * @method EventCitizen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventCitizenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventCitizen::class);
    }

    // Add custom query methods here
}
