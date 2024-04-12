<?php

namespace App\Repository;

use App\Entity\Categorie;
use App\Entity\Oeuvreart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Oeuvreart>
 *
 * @method Oeuvreart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Oeuvreart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Oeuvreart[]    findAll()
 * @method Oeuvreart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OeuvreartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oeuvreart::class);
    }

    public function trie_decroissant_date()
    {
        return $this->createQueryBuilder('oeuvreart')
            ->orderBy('oeuvreart.dateajout','DESC')
            ->getQuery()
            ->getResult();
    }
    public function findLastThreeAddedArtworks()
    {
    return $this->createQueryBuilder('oeuvre')
        ->orderBy('oeuvre.dateajout', 'DESC')
        ->setMaxResults(3)
        ->getQuery()
        ->getResult();
    }


    public function findByCategorie(Categorie $categorie)
{
    return $this->createQueryBuilder('oa')
        ->andWhere('oa.idCategorie = :categorieId')
        ->setParameter('categorieId', $categorie->getIdcategorie())
        ->getQuery()
        ->getResult();
}






//    /**
//     * @return Oeuvreart[] Returns an array of Oeuvreart objects
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

//    public function findOneBySomeField($value): ?Oeuvreart
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
