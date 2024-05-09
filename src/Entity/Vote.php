<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;


 #[ORM\Table(
    name: "vote",
    indexes: [
       new ORM\Index(name: "fk_concours", columns: ["concours"]),
       new ORM\Index(name: "fk_user", columns: ["user"]),
       new ORM\Index(name: "fk_oeuvres", columns: ["oeuvre"])
    ]
)]

#[ORM\Entity(repositoryClass: "App\Repository\VoteRepository")]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private ?int $id;

    #[ORM\Column(name: "note", type: "integer", nullable: false)]
    private ?int $note;

    #[ORM\ManyToOne(targetEntity: Oeuvreart::class)]
     #[ORM\JoinColumn(name: "oeuvre", referencedColumnName: "idoeuvreart")]
  
    private ?Oeuvreart $oeuvre;

    #[ORM\ManyToOne(targetEntity: Concours::class)]

        #[ORM\JoinColumn(name: "concours", referencedColumnName: "id")]
   
    private ?Concours $concours;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
   
        #[ORM\JoinColumn(name: "user", referencedColumnName: "id")]
  
    private ?Utilisateur $user;

    // Ajoutez les getters et les setters pour chaque propriété

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getOeuvre(): ?Oeuvreart
    {
        return $this->oeuvre;
    }

    public function setOeuvre(?Oeuvreart $oeuvre): self
    {
        $this->oeuvre = $oeuvre;

        return $this;
    }

    public function getConcours(): ?Concours
    {
        return $this->concours;
    }

    public function setConcours(?Concours $concours): self
    {
        $this->concours = $concours;

        return $this;
    }

    public function getUser(): ?Utilisateur
    {
        return $this->user;
    }

    public function setUser(?Utilisateur $user): self
    {
        $this->user = $user;

        return $this;
    }


}
