<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402115619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, startdate DATETIME NOT NULL, enddate DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, calender INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, date DATETIME NOT NULL, price DOUBLE PRECISION NOT NULL, capacite INT NOT NULL, INDEX IDX_B26681EAC56442 (calender), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateurs_evenement (id_utilisateur INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_F884160550EAE44 (id_utilisateur), INDEX IDX_F8841605FB88E14F (utilisateur_id), PRIMARY KEY(id_utilisateur, utilisateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom TEXT NOT NULL, prenom TEXT NOT NULL, email VARCHAR(100) NOT NULL, numtel INT DEFAULT NULL, login VARCHAR(100) NOT NULL, cin INT DEFAULT NULL, mdp TEXT NOT NULL, profil INT NOT NULL, image TEXT DEFAULT NULL, genre TEXT DEFAULT NULL, datenaissance DATE DEFAULT NULL, adresse TEXT DEFAULT NULL, date_inscription DATE NOT NULL, etat_compte INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EAC56442 FOREIGN KEY (calender) REFERENCES calendar (id)');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD CONSTRAINT FK_F884160550EAE44 FOREIGN KEY (id_utilisateur) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD CONSTRAINT FK_F8841605FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EAC56442');
        $this->addSql('ALTER TABLE utilisateurs_evenement DROP FOREIGN KEY FK_F884160550EAE44');
        $this->addSql('ALTER TABLE utilisateurs_evenement DROP FOREIGN KEY FK_F8841605FB88E14F');
        $this->addSql('DROP TABLE calendar');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE utilisateurs_evenement');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
