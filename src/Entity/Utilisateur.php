<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]

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
