<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panieroeuvre
 *
 * @ORM\Table(name="panieroeuvre", indexes={@ORM\Index(name="fk-id_panier", columns={"id_panier"}), @ORM\Index(name="fk-id_oeuvre", columns={"id_oeuvre"})})
 * @ORM\Entity
 */
class Panieroeuvre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var \Oeuvreart
     *
     * @ORM\ManyToOne(targetEntity="Oeuvreart")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_oeuvre", referencedColumnName="idOeuvreArt")
     * })
     */
    private $idOeuvre;

    /**
     * @var \Panier
     *
     * @ORM\ManyToOne(targetEntity="Panier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_panier", referencedColumnName="id")
     * })
     */
    private $idPanier;

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
