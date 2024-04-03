<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire", indexes={@ORM\Index(name="fk_comment_client", columns={"client_id"}), @ORM\Index(name="fk_comment_oeuvre", columns={"oeuvre_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\CommentaireRepository")
 */
class Commentaire
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
     * @var string|null
     *
     * @ORM\Column(name="commentaire", type="text", length=65535, nullable=true)
     */
    private $commentaire;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_commentaire", type="date", nullable=true)
     */
    private $dateCommentaire;




 /**
     * @var \Oeuvreart | null
     *
     * @ORM\ManyToOne(targetEntity="Oeuvreart")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="oeuvre_id", referencedColumnName="idOeuvreArt")
     * })
     */

    private $oeuvre;

    /**
     * @var \Utilisateur | null
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({@ORM\JoinColumn(name="client_id", referencedColumnName="id")})
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getDateCommentaire(): ?\DateTimeInterface
    {
        return $this->dateCommentaire;
    }

    public function setDateCommentaire(?\DateTimeInterface $dateCommentaire): static
    {
        $this->dateCommentaire = $dateCommentaire;

        return $this;
    }

    public function getOeuvre(): ?Oeuvreart
    {
        return $this->oeuvre;
    }

    public function setOeuvre(?Oeuvreart $oeuvre): static
    {
        $this->oeuvre = $oeuvre;

        return $this;
    }

    public function getClient(): ?Utilisateur
    {
        return $this->client;
    }

    public function setClient(?Utilisateur $client): static
    {
        $this->client = $client;

        return $this;
    }


}
