<?php

namespace App\Entity;
use App\Entity\Oeuvreart;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
/**
 * Concours
 *
 * @ORM\Table(name="concours")
 * @ORM\Entity
 */
class Concours
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
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=11, nullable=false)
     */
    private $titre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="date", nullable=false)
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="date", nullable=false)
     */
    private $dateFin;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=50, nullable=false)
     */
    private $description;

     // Getters
     public function getId(): ?int
     {
         return $this->id;
     }
 
     public function getTitre(): ?string
     {
         return $this->titre;
     }
 
     public function getDateDebut(): ?\DateTimeInterface
     {
         return $this->dateDebut;
     }
 
     public function getDateFin(): ?\DateTimeInterface
     {
         return $this->dateFin;
     }
 
     public function getDescription(): ?string
     {
         return $this->description;
     }
 
     // Setters
     public function setTitre(string $titre): self
     {
         $this->titre = $titre;
         return $this;
     }
 
     public function setDateDebut(\DateTimeInterface $dateDebut): self
     {
         $this->dateDebut = $dateDebut;
         return $this;
     }
 
     public function setDateFin(\DateTimeInterface $dateFin): self
     {
         $this->dateFin = $dateFin;
         return $this;
     }
 
     public function setDescription(string $description): self
     {
         $this->description = $description;
         return $this;
     }

     /**
 * @ORM\ManyToMany(targetEntity="Oeuvreart")
 * @ORM\JoinTable(name="oeuvre_concours",
 *      joinColumns={@ORM\JoinColumn(name="id_concours", referencedColumnName="id")},
 *      inverseJoinColumns={@ORM\JoinColumn(name="id_oeuvre", referencedColumnName="idOeuvreArt")}
 * )
 */
private $oeuvres;

public function __construct() {
    $this->oeuvres = new ArrayCollection();
}

public function getOeuvres(): Collection
{
    return $this->oeuvres;
}



// Dans votre classe Concours

public function getOeuvreConcours(): Collection
{
    return $this->oeuvres;
}
// Dans votre entité Concours
public function addOeuvre(Oeuvreart $oeuvre): self
{
    if (!$this->oeuvres->contains($oeuvre)) {
        $this->oeuvres[] = $oeuvre;
    }

    return $this;
}



public function removeOeuvre(Oeuvreart $oeuvre): self
{
    $this->oeuvres->removeElement($oeuvre);

    return $this;
}



}
