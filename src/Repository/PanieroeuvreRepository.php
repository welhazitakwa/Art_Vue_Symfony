<?php

namespace App\Repository;

use App\Entity\Panieroeuvre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Panieroeuvre>
 *
 * @method Panieroeuvre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panieroeuvre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panieroeuvre[]    findAll()
 * @method Panieroeuvre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanieroeuvreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panieroeuvre::class);
    }

//    /**
//     * @return Panieroeuvre[] Returns an array of Panieroeuvre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Panieroeuvre
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
