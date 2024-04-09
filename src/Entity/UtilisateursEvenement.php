<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="utilisateurs_evenement",
 *     indexes={
 *         @ORM\Index(name="id_utilisateur", columns={"id_utilisateur"}),
 *         @ORM\Index(name="id_evenement", columns={"id_evenement"})
 *     }
 * )
 */
class UtilisateursEvenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", nullable: false)]
    private $id;

    #[ORM\ManyToOne(targetEntity: Evenement::class)]
    #[ORM\JoinColumn(name: "id_evenement", referencedColumnName: "id")]
    private $idEvenement;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "id_utilisateur", referencedColumnName: "id")]
    private $idUtilisateur;

    // Getters and setters
}
