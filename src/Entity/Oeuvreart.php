<?php

namespace App\Entity;

use App\Repository\OeuvreartRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Table(name: "oeuvreart", indexes: [
    new ORM\Index(name: "fk_categorie_id", columns: ["id_categorie"]),
    new ORM\Index(name: "fk_id_artiste", columns: ["id_artiste"]),
])]

#[ORM\Entity(repositoryClass: "App\Repository\OeuvreartRepository")]

class Oeuvreart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idoeuvreart = null;

    

    #[ORM\Column(length: 255)]
    #[Assert\File(maxSize:"5M",mimeTypes:["image/jpeg","image/png","image/gif"],
    mimeTypesMessage:"Veuillez télécharger une image valide (JPEG, PNG ou GIF)")]
    #[Assert\NotBlank(message:"la photo est obligatoire")]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le titre ne peut pas être vide.")]
    #[Assert\Length(min:"3",max:"20",
    minMessage:"Le titre doit contenir au moins {{ limit }} caractères",
    maxMessage:"Le titre ne peut pas dépasser {{ limit }} caractères")]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"La description ne peut pas être vide.")]
    #[Assert\Length(min:"5",max:"800",
    minMessage:"La description doit contenir au moins {{ limit }} caractères",
    maxMessage:"La description ne peut pas dépasser {{ limit }} caractères")]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Le prix ne peut pas être vide.")]
    #[Assert\Range(
        min: 1.0,
        minMessage: "Le prix doit être d'au moins {{ limit }} DT.",
        invalidMessage: "Veuillez entrer un prix valide."
    )]
    private ?float $prixvente = null;

    #[ORM\Column(length: 255)]
    private ?string $status= 'Disponible';

      #[ORM\Column(type :"date")]
    private ?\DateTimeInterface $dateajout = null;


     #[ORM\ManyToOne(targetEntity: Categorie::class)]
     #[ORM\JoinColumn(name: "id_categorie", referencedColumnName: "idcategorie")]
     #[Assert\NotBlank(message:"Veuillez choisir une catégorie.")]
    private ?Categorie $idCategorie;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "id_artiste", referencedColumnName: "id")]
    #[Assert\NotBlank(message:"Veuillez choisir un artiste.")]
    private ?Utilisateur $idArtiste;


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
