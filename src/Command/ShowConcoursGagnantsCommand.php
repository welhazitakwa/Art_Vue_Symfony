<?php

// src/Command/ShowConcoursGagnantsCommand.php
namespace App\Command;

use App\Entity\Concours;
use App\Entity\OeuvreConcours;
use App\Entity\Vote;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ShowConcoursGagnantsCommand extends Command
{
    protected static $defaultName = 'app:show-concours-gagnants';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setDescription('Afficher les gagnants des concours qui ont pris fin');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Obtenir les concours qui ont pris fin
        $concoursFinis = $this->entityManager->getRepository(Concours::class)->findBy(['estTermine' => true]); // Modifiez selon la structure de votre entité

        foreach ($concoursFinis as $concour) {
            // Obtenir les 3 meilleurs artistes du concours
            $oeuvreConcours = $this->entityManager
                ->getRepository(OeuvreConcours::class)
                ->findBy(['idConcours' => $concour]);

            $votesParOeuvre = [];
            foreach ($oeuvreConcours as $oc) {
                $oeuvre = $oc->getIdOeuvre();
                $nombreDeVotes = $this->entityManager
                    ->getRepository(Vote::class)
                    ->count(['oeuvre' => $oeuvre, 'concours' => $concour]);

                $votesParOeuvre[] = [
                    'oeuvre' => $oeuvre,
                    'votes' => $nombreDeVotes,
                ];
            }

            usort($votesParOeuvre, function ($a, $b) {
                return $b['votes'] - $a['votes'];
            });

            $top3 = array_slice($votesParOeuvre, 0, 3);

            $io->section("Concours: " . $concour->getTitre());
            foreach ($top3 as $item) {
                $io->text("Oeuvre: " . $item['oeuvre']->getTitre() . " - Votes: " . $item['votes']);
            }
        }

        return Command::SUCCESS;
    }
}
