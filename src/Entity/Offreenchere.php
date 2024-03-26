<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Offreenchere
 *
 * @ORM\Table(name="offreenchere", indexes={@ORM\Index(name="fk_VenteEnchere", columns={"id_VenteEnchere"})})
 * @ORM\Entity
 */
class Offreenchere
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
     * @var float
     *
     * @ORM\Column(name="montant", type="float", precision=10, scale=0, nullable=false)
     */
    private $montant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_utilisateur", type="integer", nullable=true)
     */
    private $idUtilisateur;

    /**
     * @var \Venteencheres
     *
     * @ORM\ManyToOne(targetEntity="Venteencheres")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_VenteEnchere", referencedColumnName="id")
     * })
     */
    private $idVenteenchere;


}
