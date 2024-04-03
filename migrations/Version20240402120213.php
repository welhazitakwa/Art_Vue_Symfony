<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402120213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateurs_evenement DROP FOREIGN KEY FK_F88416058B13D439');
        $this->addSql('DROP INDEX IDX_F88416058B13D439 ON utilisateurs_evenement');
        $this->addSql('DROP INDEX `primary` ON utilisateurs_evenement');
        $this->addSql('ALTER TABLE utilisateurs_evenement CHANGE id_evenement id_utilisateur INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD CONSTRAINT FK_F884160550EAE44 FOREIGN KEY (id_utilisateur) REFERENCES evenement (id)');
        $this->addSql('CREATE INDEX IDX_F884160550EAE44 ON utilisateurs_evenement (id_utilisateur)');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD PRIMARY KEY (id_utilisateur, utilisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateurs_evenement DROP FOREIGN KEY FK_F884160550EAE44');
        $this->addSql('DROP INDEX IDX_F884160550EAE44 ON utilisateurs_evenement');
        $this->addSql('DROP INDEX `PRIMARY` ON utilisateurs_evenement');
        $this->addSql('ALTER TABLE utilisateurs_evenement CHANGE id_utilisateur id_evenement INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD CONSTRAINT FK_F88416058B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id)');
        $this->addSql('CREATE INDEX IDX_F88416058B13D439 ON utilisateurs_evenement (id_evenement)');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD PRIMARY KEY (id_evenement, utilisateur_id)');
    }
}
