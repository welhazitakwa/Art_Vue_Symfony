<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PanieroeuvreRepository;

#[ORM\Table(name: "panieroeuvre", indexes: [
    new ORM\Index(name: "fk-id_panier", columns: ["id_panier"]),
    new ORM\Index(name: "fk-id_oeuvre", columns: ["id_oeuvre"]),

])]

#[ORM\Entity(repositoryClass: "App\Repository\PanieroeuvreRepository")]

class Panieroeuvre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne(targetEntity: Oeuvreart::class)]
    #[ORM\JoinColumn(name: "id_oeuvre", referencedColumnName: "idoeuvreart")]
   private ?Oeuvreart $idOeuvre;

 

   #[ORM\ManyToOne(targetEntity: Panier::class)]
   #[ORM\JoinColumn(name: "id_panier", referencedColumnName: "id")]
  private ?Panier $idPanier;
  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getIdOeuvre(): ?Oeuvreart
    {
        return $this->idOeuvre;
    }

    public function setIdOeuvre(?Oeuvreart $idOeuvre): static
    {
        $this->idOeuvre = $idOeuvre;

        return $this;
    }

    public function getIdPanier(): ?Panier
    {
        return $this->idPanier;
    }

    public function setIdPanier(?Panier $idPanier): static
    {
        $this->idPanier = $idPanier;

        return $this;
    }



}
