<?php

namespace App\Repository;

use App\Entity\WasteCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WasteCollection>
 *
 * @method WasteCollection|null find($id, $lockMode = null, $lockVersion = null)
 * @method WasteCollection|null findOneBy(array $criteria, array $orderBy = null)
 * @method WasteCollection[]    findAll()
 * @method WasteCollection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WasteCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WasteCollection::class);
    }

    // Add custom query methods here
}
