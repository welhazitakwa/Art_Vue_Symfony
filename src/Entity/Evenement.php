<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'nom cannot be blank')]
    #[Assert\Length(max: 10, maxMessage: 'nom content cannot be longer than {{ limit }} characters')]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'lieu  cannot be blank')]
    #[Assert\Length(max: 10, maxMessage: 'lieu content cannot be longer than {{ limit }} characters')]
    private ?string $lieu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'price  cannot be blank')]
    #[Assert\Range(
        notInRangeMessage: 'Price must be between {{ min }} and {{ max }}',
        invalidMessage: 'Price must be a number',
        min: 0,
        max: 1000
    )]
    private ?float $price = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'capacite cannot be blank')]
    #[Assert\Range(
        notInRangeMessage: 'capacite must be between {{ min }} and {{ max }}',
        invalidMessage: 'capacite must be a number',
        min: 0,
        max: 100
    )]
    private ?int $capacite = null;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
    #[Assert\NotBlank(message: 'calender cannot be blank')]
    #[ORM\JoinColumn(name: 'calender', referencedColumnName: 'id')]
    private ?Calendar $calender = null;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, inversedBy: 'Evenement')]
    #[ORM\JoinTable(name: "utilisateurs_evenement")]
    #[ORM\JoinColumn(name: "id_evenement", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "id_utilisateur", referencedColumnName: "id")]
    private Collection $utilisateurs;

    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }
    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date ?? new \DateTimeImmutable();

        return $this;
    }


    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getCalender(): ?Calendar
    {
        return $this->calender;
    }

    public function setCalender(?Calendar $calender): static
    {
        $this->calender = $calender;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): static
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->add($utilisateur);
            $utilisateur->addEvenement($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): static
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            $utilisateur->removeEvenement($this);
        }

        return $this;
    }


}
