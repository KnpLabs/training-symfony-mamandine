<?php

namespace App\Repository;

use App\Entity\Cake;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cake|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cake|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cake[]    findAll()
 * @method Cake[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CakeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cake::class);
    }

    // Warning : SQL Injection
    // public function search(string $search = null): array
    //  {
    //     $result = $this
    //         ->getEntityManager()
    //         ->createQuery("
    //             SELECT cake FROM App\Entity\Cake cake
    //             WHERE cake.name LIKE '%".$search."%'
    //             ORDER BY cake.name DESC
    //         ")
    //         ->setMaxResults(10)
    //         ->getResult()
    //     ;

    //     return $result;
    // }

    // With Native Query (not in the exercice but could be interesting to see) / Prevent SQL injections
    // public function search(string $search = null): array
    //  {
    //     return $this
    //         ->getEntityManager()
    //         ->createQuery('
    //             SELECT cake FROM App\Entity\Cake cake
    //             WHERE cake.name LIKE :search
    //             ORDER BY cake.name DESC
    //         ')
    //         ->setParameter('search', '%'.$search.'%')
    //         ->setMaxResults(10)
    //         ->getResult()
    //     ;
    // }


    // public function search(string $search = null): array
    // {
    //     $qb = $this->createQueryBuilder('cake');

    //     if ($search) {
    //         $qb
    //             ->andWhere('cake.name like :search')
    //             ->setParameter('search', '%'.$search.'%')
    //         ;
    //     }

    //     return $qb
    //         ->orderBy('cake.name', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }

    // Final version
    public function search(string $search = null): array
    {
        $qb = $this->createQueryBuilder('cake');

        if ($search) {
            $qb
                ->andWhere($qb->expr()->like('cake.name', ':search'))
                ->setParameter('search', '%'.$search.'%')
            ;
        }

        return $qb
            ->orderBy('cake.name', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
}
