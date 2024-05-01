<?php
 
namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

 


 #[ORM\Table(name: "categorie")]
#[ORM\Entity(repositoryClass: CategorieRepository::class)]

class Categorie
{
     #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idcategorie = null;
    
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le nom du catégorie ne peut pas être vide.")]
    #[Assert\Length(min:"3",max:"20",
    minMessage:"Le nom catégorie doit contenir au moins {{ limit }} caractères",
    maxMessage:"Le nom catégorie ne peut pas dépasser {{ limit }} caractères")]
    private ?string $nomcategorie = null;


    public function getIdcategorie(): ?int
    {
        return $this->idcategorie;
    }
 
    public function setIdcategorie(int $idcategorie): static
    {
        $this->idcategorie = $idcategorie;
 
        return $this;
    }
 
    public function getNomcategorie(): ?string
    {
        return $this->nomcategorie;
    }
 
    public function setNomcategorie(string $nomcategorie): static
    {
        $this->nomcategorie = $nomcategorie;
 
        return $this;
    }
 
 
}