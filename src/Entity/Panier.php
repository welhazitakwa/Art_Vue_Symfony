<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PanierRepository;
use DateTime;
use App\Entity\Utilisateur;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
 #[ORM\Table(name: "panier", indexes: [
    new ORM\Index(name: "fk_client", columns: ["client"]),
])]

#[ORM\Entity(repositoryClass: "App\Repository\PanierRepository")]

class Panier
{
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
  

    #[ORM\Column(type: 'date')]
    private ?DateTimeInterface $dateajout = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "client", referencedColumnName: "id")]
   private ?Utilisateur $client;

   #[ORM\OneToMany(targetEntity: Panieroeuvre::class, mappedBy: 'idPanier')]
   private Collection $panieroeuvres;

   #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'panier')]
   private Collection $commandes;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateajout(): ?\DateTimeInterface
    {
        return $this->dateajout;
    }

    public function setDateajout(\DateTimeInterface $dateajout): static
    {
        $this->dateajout = $dateajout;

        return $this;
    }

    public function getClient(): ?Utilisateur
    {
        return $this->client;
    }

    public function setClient(?Utilisateur $client): static
    {
        $this->client = $client;

        return $this;
    }

   
    public function __toString(): string
    {
        return $this->getId(); // Ou une autre propriété de l'objet Panier que vous souhaitez afficher
    }
    public function __construct()
    {
        $this->panieroeuvres = new ArrayCollection();
        $this->commandes = new ArrayCollection();

    }
    /**
     * @return Collection|Panieroeuvre[]
     */
    public function getPanieroeuvres()
    {
        return $this->panieroeuvres; // Remplacez 'panieroeuvres' par le nom de votre propriété qui contient les œuvres ajoutées dans le panier
    }
 
    public function addPanieroeuvre(Panieroeuvre $panieroeuvre): self
    {
        if (!$this->panieroeuvres->contains($panieroeuvre)) {
            $this->panieroeuvres[] = $panieroeuvre;
            $panieroeuvre->setIdPanier($this);
        }

        return $this;
    }

    public function removePanieroeuvre(Panieroeuvre $panieroeuvre): self
    {
        if ($this->panieroeuvres->removeElement($panieroeuvre)) {
            // Définit le côté propriétaire à null (sauf si déjà défini)
            if ($panieroeuvre->getIdPanier() === $this) {
                $panieroeuvre->setIdPanier(null);
            }
        }

        return $this;
    }
  
   

    /**
     * @return Collection|Commande[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setPanier($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getPanier() === $this) {
                $commande->setPanier(null);
            }
        }

        return $this;
    }
}
