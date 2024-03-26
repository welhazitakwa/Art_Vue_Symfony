<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panieroeuvre
 *
 * @ORM\Table(name="panieroeuvre", indexes={@ORM\Index(name="fk-id_panier", columns={"id_panier"}), @ORM\Index(name="fk-id_oeuvre", columns={"id_oeuvre"})})
 * @ORM\Entity
 */
class Panieroeuvre
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
     * @ORM\Column(name="quantite", type="integer", nullable=false)
     */
    private $quantite;

    /**
     * @var \Oeuvreart
     *
     * @ORM\ManyToOne(targetEntity="Oeuvreart")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_oeuvre", referencedColumnName="idOeuvreArt")
     * })
     */
    private $idOeuvre;

    /**
     * @var \Panier
     *
     * @ORM\ManyToOne(targetEntity="Panier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_panier", referencedColumnName="id")
     * })
     */
    private $idPanier;


}
