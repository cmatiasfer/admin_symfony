<?php

namespace App\Repository\Remolino\CoreBundle\Entity;

use App\Remolino\CoreBundle\Entity\CmsSectionPermissions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CmsSectionPermissions|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsSectionPermissions|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsSectionPermissions[]    findAll()
 * @method CmsSectionPermissions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsSectionPermissionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmsSectionPermissions::class);
    }

//    /**
//     * @return CmsSectionPermissions[] Returns an array of CmsSectionPermissions objects
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
    public function findOneBySomeField($value): ?CmsSectionPermissions
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
