<?php

namespace App\Repository\Remolino\CoreBundle\Entity;

use App\Remolino\CoreBundle\Entity\ImagesSite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImagesSite|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImagesSite|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImagesSite[]    findAll()
 * @method ImagesSite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesSiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImagesSite::class);
    }

    // /**
    //  * @return ImagesSite[] Returns an array of ImagesSite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImagesSite
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
