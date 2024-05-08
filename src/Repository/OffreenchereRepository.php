<?php

namespace App\Repository;

use App\Entity\Offreenchere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Offreenchere>
 *
 * @method Offreenchere|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offreenchere|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offreenchere[]    findAll()
 * @method Offreenchere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreenchereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offreenchere::class);
    }

//    /**
//     * @return Offreenchere[] Returns an array of Offreenchere objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Offreenchere
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
