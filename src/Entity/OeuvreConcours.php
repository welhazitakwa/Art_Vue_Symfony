<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * OeuvreConcours
 *
 * @ORM\Table(name="oeuvre_concours", indexes={@ORM\Index(name="id_concours", columns={"id_concours"}), @ORM\Index(name="id_oeuvre", columns={"id_oeuvre"})})
 * @ORM\Entity
 */
class OeuvreConcours
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
     * @var \Concours  | null
     *
     * @ORM\ManyToOne(targetEntity="Concours")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_concours", referencedColumnName="id")
     * })
     */
    private $idConcours;

    /**
     * @var \Oeuvreart  | null
     *
     * @ORM\ManyToOne(targetEntity="Oeuvreart")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_oeuvre", referencedColumnName="idOeuvreArt")
     * })
     */
    private $idOeuvre;

// Ajoutez les getters et les setters pour chaque propriété

public function getId(): ?int
{
    return $this->id;
}

public function getIdConcours(): ?Concours
{
    return $this->idConcours;
}

public function setIdConcours(?Concours $idConcours): self
{
    $this->idConcours = $idConcours;

    return $this;
}

public function getIdOeuvre(): ?Oeuvreart
{
    return $this->idOeuvre;
}

public function setIdOeuvre(?Oeuvreart $idOeuvre): self
{
    $this->idOeuvre = $idOeuvre;

    return $this;
}

}
