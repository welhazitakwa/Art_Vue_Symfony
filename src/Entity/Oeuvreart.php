<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Oeuvreart
 *
 * @ORM\Table(name="oeuvreart", indexes={@ORM\Index(name="fk_categorie_id", columns={"id_categorie"}), @ORM\Index(name="fk_id_artiste", columns={"id_artiste"})})
 * @ORM\Entity(repositoryClass="App\Repository\OeuvreartRepository")
 */
class Oeuvreart
{
    /**
     * @var int
     *
     * @ORM\Column(name="idOeuvreArt", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idoeuvreart;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=0, nullable=false)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="prixVente", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixvente;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false)
     */
    private $status= 'Disponible';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAjout", type="date", nullable=false)
     */
    private $dateajout;

    /**
     * @var \Categorie|null
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie", referencedColumnName="idCategorie")
     * })
     */
    private $idCategorie;

    /**
     * @var \Utilisateur|null
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_artiste", referencedColumnName="id")
     * })
     */
    private $idArtiste;

    public function getIdoeuvreart(): ?int
    {
        return $this->idoeuvreart;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getPrixvente(): ?float
    {
        return $this->prixvente;
    }

    public function setPrixvente(float $prixvente): static
    {
        $this->prixvente = $prixvente;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
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

    public function getIdCategorie(): ?Categorie
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?Categorie $idCategorie): static
    {
        $this->idCategorie = $idCategorie;
        return $this;
    }

    public function getIdArtiste(): ?Utilisateur
    {
        return $this->idArtiste;
    }

    public function setIdArtiste(?Utilisateur $idArtiste): static
    {
        $this->idArtiste = $idArtiste;
        return $this;
    }
}
