<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Likes
 *
 * @ORM\Table(name="likes", indexes={@ORM\Index(name="idUtilisateur", columns={"idUtilisateur"}), @ORM\Index(name="idOeuvreArt", columns={"idOeuvreArt"})})
 * @ORM\Entity
 */
class Likes
{
    /**
     * @var int
     *
     * @ORM\Column(name="idLike", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idlike;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="estLike", type="boolean", nullable=true)
     */
    private $estlike;

    /**
     * @var \Oeuvreart
     *
     * @ORM\ManyToOne(targetEntity="Oeuvreart")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idOeuvreArt", referencedColumnName="idOeuvreArt")
     * })
     */
    private $idoeuvreart;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idUtilisateur", referencedColumnName="id")
     * })
     */
    private $idutilisateur;

    public function getIdlike(): ?int
    {
        return $this->idlike;
    }

    public function isEstlike(): ?bool
    {
        return $this->estlike;
    }

    public function setEstlike(?bool $estlike): static
    {
        $this->estlike = $estlike;

        return $this;
    }

    public function getIdoeuvreart(): ?Oeuvreart
    {
        return $this->idoeuvreart;
    }

    public function setIdoeuvreart(?Oeuvreart $idoeuvreart): static
    {
        $this->idoeuvreart = $idoeuvreart;

        return $this;
    }

    public function getIdutilisateur(): ?Utilisateur
    {
        return $this->idutilisateur;
    }

    public function setIdutilisateur(?Utilisateur $idutilisateur): static
    {
        $this->idutilisateur = $idutilisateur;

        return $this;
    }


}
