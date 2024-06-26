<?php

namespace App\Repository;



use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Utilisateur>
 *
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }


//    /**
//     * @return Utilisateur[] Returns an array of Utilisateur objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Utilisateur
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }



// public function validateLogin($loginFourni, $mdpFourni): int {
//     $profilUser = 3;
//     $em = $this->getEntityManager();
//     $queryBuilder = $em->createQueryBuilder();

//     // Vérifier si l'utilisateur avec le login fourni existe
//     $queryBuilder->select('COUNT(u.id)')
//                  ->from(Utilisateur::class, 'u')
//                  ->where('u.login = :login')
//                  ->setParameter('login', $loginFourni);
    
//     $count = $queryBuilder->getQuery()->getSingleScalarResult();

//     if ($count == 1) {
//         // Récupérer le mot de passe de l'utilisateur
//         $queryBuilder = $em->createQueryBuilder();
//         $queryBuilder->select('u.mdp')
//                      ->from(Utilisateur::class, 'u')
//                      ->where('u.login = :login')
//                      ->setParameter('login', $loginFourni);
        
//         $mdpfromdatabase = $queryBuilder->getQuery()->getSingleScalarResult();

//         // Vérifier si le mot de passe fourni correspond
//         $mdphashed = $this->checkExistingUser($mdpFourni, $mdpfromdatabase);
//         if ($mdphashed) {
//             // Authentification réussie, récupérer le profil de l'utilisateur
//             $queryBuilder = $em->createQueryBuilder();
//             $queryBuilder->select('u.profil')
//                          ->from(Utilisateur::class, 'u')
//                          ->where('u.login = :login')
//                          ->setParameter('login', $loginFourni);
            
//             $profilUser = $queryBuilder->getQuery()->getSingleScalarResult();
//             echo "welcome !!";
//         } else {
//             echo "mawelcomch !!";
//         }
//     } else {
//         echo "verifiez vos parametres d'authentification !! ";
//     }

//     return $profilUser;
// }

public function login($login, $mdp)
{
    if ($login == "") {
        return "Vous devez remplir le champ de login";
    }
    if ($mdp == "") {
        return "Vous devez saisir votre mot de passe";
    }
    // Récupérer l'utilisateur par le login
    $utilisateur = $this->createQueryBuilder('u')
        ->where('u.login = :login')
        ->setParameter('login', $login)
        ->getQuery()
        ->getOneOrNullResult();
        
    // Utilisateur non trouvé
    
    if (!$utilisateur) {
        return "Vérifier Vos Données";
    }
    // Récupérer le mot de passe haché de l'utilisateur
    $hashedPassword = $utilisateur->getMdp();

    // Vérifier si le mot de passe est correct
    if (password_verify($mdp, $hashedPassword)) {
      return $utilisateur;
    } else {
               return "Vérifier Vos Données"; 
    }
// echo $mdp;
// echo $hashedPassword;           
}


public function verifEmail($email)
{
    if ($email == "") {
        return "Ce champ est Obligatoire !";
    }
    // Récupérer l'utilisateur par le email
    $utilisateur = $this->createQueryBuilder('u')
        ->where('u.email = :email')
        ->setParameter('email', $email)
        ->getQuery()
        ->getOneOrNullResult();
        
    // Utilisateur non trouvé
    
    if (!$utilisateur) {
        return "aucun utilisateur n'est trouvé avec cet email";
    } else {
              return $utilisateur;
    }
               
}

}

