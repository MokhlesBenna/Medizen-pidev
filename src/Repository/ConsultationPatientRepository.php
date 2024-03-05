<?php

namespace App\Repository;

use App\Entity\ConsultationPatient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ConsultationPatient>
 *
 * @method ConsultationPatient|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsultationPatient|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsultationPatient[]    findAll()
 * @method ConsultationPatient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsultationPatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsultationPatient::class);
    }

//    /**
//     * @return ConsultationPatient[] Returns an array of ConsultationPatient objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ConsultationPatient
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
