<?php

namespace App\Repository\Remolino\CoreBundle\Entity;

use App\Remolino\CoreBundle\Entity\CmsBlocks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CmsBlocks|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsBlocks|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsBlocks[]    findAll()
 * @method CmsBlocks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsBlocksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmsBlocks::class);
    }

//    /**
//     * @return CmsBlocks[] Returns an array of CmsBlocks objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CmsBlocks
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
