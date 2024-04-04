<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Categorie
 *
 * @ORM\Table(name="categorie")
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 */

class Categorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="idCategorie", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idcategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="nomCategorie", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Le nom de la catégorie ne peut pas être vide.")
     * @Assert\Length(
     *      min = 2,
     *      max = 20,
     *      minMessage = "Le nom de la catégorie doit contenir au moins {{ limit }} caractères.",
     *      maxMessage = "Le nom de la catégorie ne peut pas dépasser {{ limit }} caractères."
     * )
     * @Assert\Regex(
     * pattern="/^[A-Za-z]+$/",
     * message="Le nom de la catégorie doit contenir uniquement des lettres."
     * )
     */
    private $nomcategorie;
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
