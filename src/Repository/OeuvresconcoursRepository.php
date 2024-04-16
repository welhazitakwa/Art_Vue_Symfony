<?php

namespace App\Repository;

use App\Entity\OeuvreConcours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OeuvreConcours>
 *
 * @method OeuvreConcours|null find($id, $lockMode = null, $lockVersion = null)
 * @method OeuvreConcours|null findOneBy(array $criteria, array $orderBy = null)
 * @method OeuvreConcours[]    findAll()
 * @method OeuvreConcours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OeuvresconcoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OeuvreConcours::class);
    }

//    /**
//     * @return OeuvreConcours[] Returns an array of OeuvreConcours objects
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

//    public function findOneBySomeField($value): ?OeuvreConcours
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
