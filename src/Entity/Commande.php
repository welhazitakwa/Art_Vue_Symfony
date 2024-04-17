<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;

 #[ORM\Table(name: "commande", indexes: [
    new ORM\Index(name: "fk_panier", columns: ["panier"]),
])]

#[ORM\Entity(repositoryClass: "App\Repository\CommandeRepository")]

class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column(type :"date")]
    private ?\DateTimeInterface $date = null;

   #[ORM\Column(length: 255)]
    private ?string $etat = null;
  

    #[ORM\ManyToOne(targetEntity: Panier::class)]
    #[ORM\JoinColumn(name: "panier", referencedColumnName: "id")]
   private ?Panier $panier;

 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): static
    {
        $this->panier = $panier;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getId(); // Ou une autre propriété de l'objet Panier que vous souhaitez afficher
    }


    


}
