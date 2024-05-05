<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240413011024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
   
        $this->addSql('ALTER TABLE panier CHANGE client client INT DEFAULT NULL, CHANGE dateAjout dateajout DATE NOT NULL');
        }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE panier CHANGE client client INT NOT NULL, CHANGE dateajout dateAjout DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL');
        }
}
