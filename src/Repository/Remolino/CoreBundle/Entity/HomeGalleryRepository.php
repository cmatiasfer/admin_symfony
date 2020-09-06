<?php

namespace App\Repository\Remolino\CoreBundle\Entity;

use App\Remolino\CoreBundle\Entity\HomeGallery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HomeGallery|null find($id, $lockMode = null, $lockVersion = null)
 * @method HomeGallery|null findOneBy(array $criteria, array $orderBy = null)
 * @method HomeGallery[]    findAll()
 * @method HomeGallery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HomeGalleryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HomeGallery::class);
    }

    public function countRow()
    {
        return $this->createQueryBuilder('h')
                ->select("count(h.id)")
                ->getQuery()
                ->getSingleScalarResult();
    }

    // /**
    //  * @return HomeGallery[] Returns an array of HomeGallery objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HomeGallery
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
