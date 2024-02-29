<?php

namespace App\Repository;

use App\Entity\Sponseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sponseur>
 *
 * @method Sponseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sponseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sponseur[]    findAll()
 * @method Sponseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SponseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sponseur::class);
    }

//    /**
//     * @return Sponseur[] Returns an array of Sponseur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sponseur
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
