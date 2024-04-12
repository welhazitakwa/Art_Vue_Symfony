<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
#[ORM\Table(name: "livraison", indexes: [
    new ORM\Index(name: "fk_commande", columns: ["commande"]),
])]
#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?float $frais = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\ManyToOne(targetEntity: Commande::class)]
    #[ORM\JoinColumn(name: "commande", referencedColumnName: "id")]
   private ?Commande $commande;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getFrais(): ?float
    {
        return $this->frais;
    }

    public function setFrais(float $frais): static
    {
        $this->frais = $frais;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }
}
