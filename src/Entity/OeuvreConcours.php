<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(
    name: "oeuvre_concours",
    indexes: [
       new ORM\Index(name: "oeuvre_concours_ibfk_1", columns: ["id_concours"]),
       new ORM\Index(name: "oeuvre_concours_ibfk_2", columns: ["id_oeuvre"])
    ]
)]

#[ORM\Entity(repositoryClass: "App\Repository\OeuvresconcoursRepository")]
class OeuvreConcours
{#[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Concours::class)]
    
        #[ORM\JoinColumn(name: "id_concours", referencedColumnName: "id")]
  
    private ?Concours $idConcours;

    #[ORM\ManyToOne(targetEntity: Oeuvreart::class)]
    
        #[ORM\JoinColumn(name: "id_oeuvre", referencedColumnName: "idoeuvreart")]
   
    private ?Oeuvreart $idOeuvre;
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
