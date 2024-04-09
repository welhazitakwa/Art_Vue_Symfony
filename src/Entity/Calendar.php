<?php

namespace App\Entity;

use App\Repository\CalendarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CalendarRepository::class)]
class Calendar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'nom cannot be blank')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: 'Name must contain only letters'
    )]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'nom cannot be blank')]
    private ?\DateTimeInterface $startdate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: 'nom cannot be blank')]
    private ?\DateTimeInterface $enddate = null;

    #[ORM\OneToMany(targetEntity: Evenement::class, mappedBy: 'calender')]
    private Collection $evenements;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->startdate;
    }
    public function setStartdate(?\DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate ?? new \DateTimeImmutable();

        return $this;
    }


    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->enddate;
    }


    public function setEnddate(?\DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate ?? new \DateTimeImmutable();

        return $this;
    }
    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): static
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->setCalender($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): static
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getCalender() === $this) {
                $evenement->setCalender(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->name;
    }
}
