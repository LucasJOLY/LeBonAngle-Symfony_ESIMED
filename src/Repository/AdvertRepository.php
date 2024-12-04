<?php

namespace App\Repository;

use App\Entity\Advert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Advert>
 */
class AdvertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advert::class);
    }


    public function findRejectedBefore(\DateTimeImmutable $date): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.state = :state')
            ->andWhere('a.createdAt <= :date')
            ->setParameter('state', 'rejected')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }


    public function findPublishedBefore(\DateTimeImmutable $date): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.state = :state')
            ->andWhere('a.publishedAt <= :date')
            ->setParameter('state', 'published')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }


//    /**
//     * @return Advert[] Returns an array of Advert objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Advert
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
