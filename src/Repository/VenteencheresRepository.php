<?php

namespace App\Repository;

use App\Entity\Venteencheres;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Venteencheres>
 *
 * @method Venteencheres|null find($id, $lockMode = null, $lockVersion = null)
 * @method Venteencheres|null findOneBy(array $criteria, array $orderBy = null)
 * @method Venteencheres[]    findAll()
 * @method Venteencheres[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VenteencheresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Venteencheres::class);
    }

    public function search($searchTerm)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.id LIKE :searchTerm OR v.datedebut LIKE :searchTerm OR v.datefin LIKE :searchTerm OR v.prixdepart LIKE :searchTerm OR v.statue LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Venteencheres[] Returns an array of Venteencheres objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Venteencheres
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
