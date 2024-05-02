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

public function findByNom($titre)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.titre LIKE :titre')
            ->setParameter('titre', '%'.$titre.'%')
            ->getQuery()
            ->getResult();
    }

    public function findByArtist($artistName)
{
    return $this->createQueryBuilder('o')
        ->leftJoin('o.idArtiste', 'a')
        ->andWhere('a.nom LIKE :artistName OR a.prenom LIKE :artistName')
        ->setParameter('artistName', '%' . $artistName . '%')
        ->getQuery()
        ->getResult();
}

public function findByArtistAndCategory($artistName, $categoryId)
{
    $qb = $this->createQueryBuilder('oa');

    // Ajouter la condition de recherche par artiste
    if ($artistName) {
        $qb->join('oa.idArtiste', 'a')
        ->andWhere('a.nom LIKE :artistName OR a.prenom LIKE :artistName')
        ->setParameter('artistName', '%' . $artistName . '%');
    }

    // Ajouter la condition de recherche par catégorie
    if ($categoryId) {
        $qb->join('oa.idCategorie', 'c')
           ->andWhere('c.idcategorie = :categoryId')
           ->setParameter('categoryId', $categoryId);
    }

    return $qb->getQuery()->getResult();
}

public function getPrixVenteData()
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.idoeuvreart) as count, o.prixvente as price')
            ->groupBy('o.prixvente')
            ->getQuery()
            ->getResult();
    }
    
    







//    /**
//     * @return Oeuvreart[] Returns an array of Oeuvreart objects
//     */
   public function findByExampleField($value): array
   {
       return $this->createQueryBuilder('o')
           ->andWhere('o.exampleField = :val')
           ->setParameter('val', $value)
           ->orderBy('o.id', 'ASC')
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   public function findOneBySomeField($value): ?Oeuvreart
   {
       return $this->createQueryBuilder('o')
           ->andWhere('o.exampleField = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
