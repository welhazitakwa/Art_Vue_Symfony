<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Offreenchere
 *
 * @ORM\Table(name="offreenchere", indexes={@ORM\Index(name="fk_VenteEnchere", columns={"id_VenteEnchere"})})
 * @ORM\Entity
 */
class Offreenchere
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
     * @var float
     *
     * @ORM\Column(name="montant", type="float", precision=10, scale=0, nullable=false)
     */
    private $montant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_utilisateur", type="integer", nullable=true)
     */
    private $idUtilisateur;

    /**
     * @var \Venteencheres
     *
     * @ORM\ManyToOne(targetEntity="Venteencheres")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_VenteEnchere", referencedColumnName="id")
     * })
     */
    private $idVenteenchere;

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

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?int $idUtilisateur): static
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    public function getIdVenteenchere(): ?Venteencheres
    {
        return $this->idVenteenchere;
    }

    public function setIdVenteenchere(?Venteencheres $idVenteenchere): static
    {
        $this->idVenteenchere = $idVenteenchere;

        return $this;
    }


}
