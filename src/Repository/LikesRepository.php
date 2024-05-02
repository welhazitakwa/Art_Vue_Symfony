<?php

namespace App\Repository;

use App\Entity\Likes;
use App\Entity\Oeuvreart;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Likes>
 *
 * @method Likes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Likes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Likes[]    findAll()
 * @method Likes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Likes::class);
    }

    

   /**
    * @return Likes[] Returns an array of Likes objects
    */
   public function findByExampleField($value): array
   {
       return $this->createQueryBuilder('l')
           ->andWhere('l.exampleField = :val')
           ->setParameter('val', $value)
           ->orderBy('l.id', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   public function countLikesForOeuvreArt(int $oeuvreArtId): int
{
    return $this->createQueryBuilder('l')
        ->select('COUNT(l.idlike)')
        ->where('l.idoeuvreart = :oeuvreArtId')
        ->setParameter('oeuvreArtId', $oeuvreArtId)
        ->getQuery()
        ->getSingleScalarResult();
}



   public function findOneBySomeField($value): ?Likes
   {
       return $this->createQueryBuilder('l')
           ->andWhere('l.exampleField = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
