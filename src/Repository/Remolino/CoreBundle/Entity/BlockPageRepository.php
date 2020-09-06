<?php

namespace App\Repository\Remolino\CoreBundle\Entity;

use App\Remolino\CoreBundle\Entity\BlockPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlockPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlockPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlockPage[]    findAll()
 * @method BlockPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlockPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlockPage::class);
    }

    // /**
    //  * @return BlockPage[] Returns an array of BlockPage objects
    //  */
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
    public function findOneBySomeField($value): ?BlockPage
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
