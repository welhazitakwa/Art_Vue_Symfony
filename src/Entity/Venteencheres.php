<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Venteencheres
 *
 * @ORM\Table(name="venteencheres", indexes={@ORM\Index(name="fk_Exposition", columns={"id_exposition"}), @ORM\Index(name="id_utilisateur", columns={"id_utilisateur"})})
 * @ORM\Entity
 */
class Venteencheres
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="date", nullable=false)
     */
    private $datedebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="date", nullable=false)
     */
    private $datefin;

    /**
     * @var float
     *
     * @ORM\Column(name="prixDepart", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixdepart;

    /**
     * @var string
     *
     * @ORM\Column(name="statue", type="string", length=200, nullable=false)
     */
    private $statue;

    /**
     * @var \Exposition
     *
     * @ORM\ManyToOne(targetEntity="Exposition")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_exposition", referencedColumnName="id")
     * })
     */
    private $idExposition;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_utilisateur", referencedColumnName="id")
     * })
     */
    private $idUtilisateur;


}
