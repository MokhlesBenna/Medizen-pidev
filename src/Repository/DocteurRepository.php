<?php

namespace App\Repository;

use App\Entity\Docteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Docteur>
 *
 * @method Docteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Docteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Docteur[]    findAll()
 * @method Docteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Docteur::class);
    }

//    /**
//     * @return Docteur[] Returns an array of Docteur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Docteur
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
