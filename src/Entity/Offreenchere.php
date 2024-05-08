<?php

namespace App\Entity;

use App\Repository\OffreenchereRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreenchereRepository::class)]
class Offreenchere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'float')]
    private float $montant;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $idUtilisateur;

    #[ORM\ManyToOne(targetEntity: Venteencheres::class)]
    #[ORM\JoinColumn(name: 'id_VenteEnchere', referencedColumnName: 'id')]
    private ?Venteencheres $idVenteenchere;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?int $idUtilisateur): self
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    public function getIdVenteenchere(): ?Venteencheres
    {
        return $this->idVenteenchere;
    }

    public function setIdVenteenchere(?Venteencheres $idVenteenchere): self
    {
        $this->idVenteenchere = $idVenteenchere;

        return $this;
    }
}
