<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(
    name: "utilisateur",
    uniqueConstraints: [
        new ORM\UniqueConstraint(name: "login", columns: ["login"]),
        new ORM\UniqueConstraint(name: "email", columns: ["email"])
    ]
)]
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:"2",
    minMessage:"Votre nom doit contenir au moin 2 lettres")]
    private ?string $nom = null;


    #[ORM\Column(length: 255)]
    #[Assert\Length(min:"2",
    minMessage:"Votre nom doit contenir au moin 2 lettres")]
    private ?string $prenom = null;

   
    #[ORM\Column(length: 255)]  
    #[Assert\Email(message: "l'email {{ value }} is not a valid email.",)]
    private ?string $email = null;


    #[ORM\Column]
    #[Assert\Length(min:"8",max:"8",
    minMessage:"Le numéro de téléphone avoir exactement 8 chiffres",
    maxMessage:"Le numéro de téléphone avoir exactement 8 chiffres")]
    private ?int $numtel = null;

    

     #[ORM\Column(length: 255)]
   #[Assert\Length(min:"1",
    minMessage:"Ce champ ne doit pas etre vide")]
    private ?string $login = null;


   #[ORM\Column]
   #[Assert\Length(min:"8",max:"8",
    minMessage:"Le numéro de téléphone avoir exactement 8 chiffres",
    maxMessage:"Le numéro de téléphone avoir exactement 8 chiffres")]
    private ?int $cin = null;

    #[ORM\Column(length: 255)]
    private ?string $mdp = null;


   #[ORM\Column]
    private ?int $profil = null;

     #[ORM\Column(length: 255)]
    private ?string $image = null;


     #[ORM\Column(length: 255)]
    private ?string $genre = null;


    #[ORM\Column(type :"date")]
    private ?\DateTimeInterface $datenaissance = null;


     #[ORM\Column(length: 255)]
    private ?string $adresse = null;


    #[ORM\Column(type: "date")]
    private ?\DateTimeInterface $dateInscription = null;

    #[ORM\Column]
    private ?int $etatCompte = null;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(?int $numtel): static
    {
        $this->numtel = $numtel;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(?int $cin): static
    {
        $this->cin = $cin;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getProfil(): ?int
    {
        return $this->profil;
    }

    public function setProfil(int $profil): static
    {
        $this->profil = $profil;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getDatenaissance(): ?\DateTimeInterface
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(?\DateTimeInterface $datenaissance): static
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getEtatCompte(): ?int
    {
        return $this->etatCompte;
    }

    public function setEtatCompte(int $etatCompte): static
    {
        $this->etatCompte = $etatCompte;

        return $this;
    }

    


}
