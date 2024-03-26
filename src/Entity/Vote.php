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
     * @var \Oeuvreart
     *
     * @ORM\ManyToOne(targetEntity="Oeuvreart")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="oeuvre", referencedColumnName="idOeuvreArt")
     * })
     */
    private $oeuvre;

    /**
     * @var \Concours
     *
     * @ORM\ManyToOne(targetEntity="Concours")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="concours", referencedColumnName="id")
     * })
     */
    private $concours;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;


}
