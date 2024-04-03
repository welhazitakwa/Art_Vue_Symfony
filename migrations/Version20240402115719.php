<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402115719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateurs_evenement DROP FOREIGN KEY FK_F8841605FB88E14F');
        $this->addSql('ALTER TABLE utilisateurs_evenement DROP FOREIGN KEY FK_F884160550EAE44');
        $this->addSql('DROP INDEX IDX_F884160550EAE44 ON utilisateurs_evenement');
        $this->addSql('DROP INDEX IDX_F8841605FB88E14F ON utilisateurs_evenement');
        $this->addSql('DROP INDEX `primary` ON utilisateurs_evenement');
        $this->addSql('ALTER TABLE utilisateurs_evenement DROP id_utilisateur');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD PRIMARY KEY (utilisateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `PRIMARY` ON utilisateurs_evenement');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD id_utilisateur INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD CONSTRAINT FK_F8841605FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD CONSTRAINT FK_F884160550EAE44 FOREIGN KEY (id_utilisateur) REFERENCES evenement (id)');
        $this->addSql('CREATE INDEX IDX_F884160550EAE44 ON utilisateurs_evenement (id_utilisateur)');
        $this->addSql('CREATE INDEX IDX_F8841605FB88E14F ON utilisateurs_evenement (utilisateur_id)');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD PRIMARY KEY (id_utilisateur, utilisateur_id)');
    }
}
