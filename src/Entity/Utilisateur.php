<?php

namespace App\Entity;


use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]


/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur", uniqueConstraints={@ORM\UniqueConstraint(name="login", columns={"login"}), @ORM\UniqueConstraint(name="email", columns={"email"})})
 * @ORM\Entity(repositoryClass="App\Repository\UtilisateurRepository")
 */


class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(type: "text", length: 65535, nullable: false)]
    private $nom;

    #[ORM\Column(type: "text", length: 65535, nullable: false)]
    private $prenom;

    #[ORM\Column(type: "string", length: 100, nullable: false)]
    private $email;

    #[ORM\Column(type: "integer", nullable: true)]
    private $numtel;

    #[ORM\Column(type: "string", length: 100, nullable: false)]
    private $login;

    #[ORM\Column(type: "integer", nullable: true)]
    private $cin;

    #[ORM\Column(type: "text", length: 65535, nullable: false)]
    private $mdp;

    #[ORM\Column(type: "integer", nullable: false)]
    private $profil;

    #[ORM\Column(type: "text", length: 65535, nullable: true)]
    private $image;

    #[ORM\Column(type: "text", length: 65535, nullable: true)]
    private $genre;

    #[ORM\Column(type: "date", nullable: true)]
    private $datenaissance;

    #[ORM\Column(type: "text", length: 65535, nullable: true)]
    private $adresse;

    #[ORM\Column(type: "date", nullable: false)]
    private $dateInscription;

    #[ORM\Column(type: "integer", nullable: false)]
    private $etatCompte;

    #[ORM\ManyToMany(targetEntity: Evenement::class, mappedBy: 'utilisateurs')]
    private Collection $Evenement;

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

    



    public function __construct()
    {
        $this->Evenement = new ArrayCollection();
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenement(): Collection
    {
        return $this->Evenement;
    }

    public function addEvenement(Evenement $evenement): static
    {
        if (!$this->Evenement->contains($evenement)) {
            $this->Evenement->add($evenement);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): static
    {
        $this->Evenement->removeElement($evenement);

        return $this;
    }
    public function __toString(): string
    {
        return $this->nom . ' (' . $this->email . ')';
    }       

}
