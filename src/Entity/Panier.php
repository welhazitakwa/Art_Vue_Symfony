<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTime ;
use App\Entity\Utilisateur;

/**
 * Panier
 *
 * @ORM\Table(name="panier", 
 * indexes={@ORM\Index(name="fk_client", 
 * columns={"client"})})
 * @ORM\Entity
 */

class Panier
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
     * @ORM\Column(name="dateAjout", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $dateajout = 'CURRENT_TIMESTAMP';

   /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client", referencedColumnName="id")
     * })
     */
    private $client;

    public function __construct()
    {
        $this->dateajout = new DateTime();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateajout(): \DateTime
    {
        return $this->dateajout;
    }
    

    public function setDateajout(\DateTime $dateajout): static
    {
        $this->dateajout = $dateajout;

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

 /**
     * Méthode pour convertir un objet Panier en chaîne de caractères.
     *
     * @return string
     */
    public function __toString()
    {
        // Retournez une représentation sous forme de chaîne de l'objet Panier
        // Par exemple, vous pouvez retourner le nom du client si c'est pertinent
        return $this->getClient()->getNom(); // Assurez-vous que getClient() retourne l'entité Utilisateur/Client associée au panier et que cette entité a une méthode getNom()
    }
}
