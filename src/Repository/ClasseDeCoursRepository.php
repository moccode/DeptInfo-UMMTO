<?php

namespace App\Repository;

use App\Entity\ClasseDeCours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClasseDeCours|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClasseDeCours|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClasseDeCours[]    findAll()
 * @method ClasseDeCours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClasseDeCoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClasseDeCours::class);
    }

    // /**
    //  * @return ClasseDeCours[] Returns an array of ClasseDeCours objects
    //  */
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
    public function findOneBySomeField($value): ?ClasseDeCours
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
