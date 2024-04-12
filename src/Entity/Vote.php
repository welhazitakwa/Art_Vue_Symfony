<?php

namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * Vote
 *
 * @ORM\Table(name="vote", indexes={@ORM\Index(name="fk_concours", columns={"concours"}), @ORM\Index(name="fk_user", columns={"user"}), @ORM\Index(name="fk_oeuvres", columns={"oeuvre"})})
 * @ORM\Entity
 */
class Vote
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
     * @var int
     *
     * @ORM\Column(name="note", type="integer", nullable=false)
     */
    private $note;

    /**
     * @var \Oeuvreart  | null
     *
     * @ORM\ManyToOne(targetEntity="Oeuvreart")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="oeuvre", referencedColumnName="idOeuvreArt")
     * })
     */
    private $oeuvre;

    /**
     * @var \Concours  | null
     *
     * @ORM\ManyToOne(targetEntity="Concours")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="concours", referencedColumnName="id")
     * })
     */
    private $concours;

    /**
     * @var \Utilisateur  | null
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;

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
