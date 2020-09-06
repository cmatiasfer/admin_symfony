<?php

namespace App\Repository\Remolino\CoreBundle\Entity;

use App\Remolino\CoreBundle\Entity\Texts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Texts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Texts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Texts[]    findAll()
 * @method Texts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Texts::class);
    }


    public function findAdmin($parameters, $limit)
    {
        $query = $this->createQueryBuilder('p')
            ->addSelect("p");
        if(isset($parameters["texts_texto"]) && $parameters["texts_texto"] != ""){
            $query->andWhere('p.variable LIKE :_texto');
            $query->orWhere('p.textEN LIKE :_texto');
            $query->orWhere('p.textES LIKE :_texto');
            $query->orWhere('p.titleEN LIKE :_texto');
            $query->orWhere('p.titleES LIKE :_texto');
            $query->orWhere('p.seoTitle LIKE :_texto');
            $query->orWhere('p.seoDesc LIKE :_texto');
            $query->setParameter('_texto', '%'.$parameters["texts_texto"].'%');
        }

        if($limit){
            $query->setFirstResult($parameters["start"]);
            $query->setMaxResults($parameters["length"]);
        }
        $result = $query->getQuery()->getResult();
        return $result;
    }

    // /**
    //  * @return Texts[] Returns an array of Texts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Texts
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
