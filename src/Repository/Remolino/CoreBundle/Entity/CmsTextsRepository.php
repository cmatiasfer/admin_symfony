<?php

namespace App\Repository\Remolino\CoreBundle\Entity;

use App\Remolino\CoreBundle\Entity\CmsTexts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CmsTexts|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsTexts|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsTexts[]    findAll()
 * @method CmsTexts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsTextsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmsTexts::class);
    }

//    /**
//     * @return CmsTexts[] Returns an array of CmsTexts objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CmsTexts
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
