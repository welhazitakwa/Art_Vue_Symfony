<?php

namespace App\Entity;

use App\Repository\VenteencheresRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VenteencheresRepository::class)]
class Venteencheres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $datedebut;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $datefin;

    #[ORM\Column(type: 'float')]
    private float $prixdepart;

    #[ORM\Column(type: 'string', length: 200)]
    private string $statue;

    #[ORM\ManyToOne(targetEntity: Exposition::class)]
    #[ORM\JoinColumn(name: 'id_exposition', referencedColumnName: 'id')]
    private ?Exposition $idExposition;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id')]
    private ?Utilisateur $idUtilisateur;

    public function getId(): ?int
    {
        return $this->id;
    }

    // Ajoutez les getters et setters pour les autres propriétés

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): static
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDatefin(): ?\DateTimeInterface
    {
        return $this->datefin;
    }

    public function setDatefin(\DateTimeInterface $datefin): static
    {
        $this->datefin = $datefin;

        return $this;
    }

    public function getPrixdepart(): ?float
    {
        return $this->prixdepart;
    }

    public function setPrixdepart(float $prixdepart): static
    {
        $this->prixdepart = $prixdepart;

        return $this;
    }

    public function getStatue(): ?string
    {
        return $this->statue;
    }

    public function setStatue(string $statue): static
    {
        $this->statue = $statue;

        return $this;
    }

    public function getIdExposition(): ?Exposition
    {
        return $this->idExposition;
    }

    public function setIdExposition(?Exposition $idExposition): static
    {
        $this->idExposition = $idExposition;

        return $this;
    }

    public function getIdUtilisateur(): ?Utilisateur
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?Utilisateur $idUtilisateur): static
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }
    
 
}
