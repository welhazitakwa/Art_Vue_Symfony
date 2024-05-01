<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UtilisateurRepository;
use App\Repository\OeuvreartRepository;

#[ORM\Table(name: "likes", indexes: [
    new ORM\Index(name: "idUtilisateur", columns: ["idUtilisateur"]),
    new ORM\Index(name: "idOeuvreArt", columns: ["idOeuvreArt"]),
])]

#[ORM\Entity(repositoryClass: "App\Repository\LikesRepository")]
class Likes
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "idLike", type: "integer", nullable: false)]
    private ?int $idlike;

    #[ORM\Column(name: "estLike", type: "boolean", nullable: true)]
    private ?bool $estlike;

    #[ORM\ManyToOne(targetEntity: Oeuvreart::class)]
    #[ORM\JoinColumn(name: "idOeuvreArt", referencedColumnName: "idoeuvreart")]
    private ?Oeuvreart $idoeuvreart;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "idUtilisateur", referencedColumnName: "id")]
    private ?Utilisateur $idutilisateur;

    public function getIdlike(): ?int
    {
        return $this->idlike;
    }

    public function getEstlike(): ?bool
    {
        return $this->estlike;
    }

    public function setEstlike(bool $estlike): static
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
