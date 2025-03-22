<?php

namespace App\Repository;

use App\Entity\React;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<React>
 *
 * @method React|null find($id, $lockMode = null, $lockVersion = null)
 * @method React|null findOneBy(array $criteria, array $orderBy = null)
 * @method React[]    findAll()
 * @method React[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, React::class);
    }

    // Add custom query methods here
}
