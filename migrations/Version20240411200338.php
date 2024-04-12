<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240411200338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livraison (id INT AUTO_INCREMENT NOT NULL, commande INT DEFAULT NULL, adresse VARCHAR(255) NOT NULL, frais DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, INDEX IDX_A60C9F1F6EEAA67D (commande), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F6EEAA67D FOREIGN KEY (commande) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY commentaire_ibfk_1');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY kfff');
        $this->addSql('ALTER TABLE exposition DROP FOREIGN KEY exposition_ibfk_1');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY likes_ibfk_2');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY likes_ibfk_1');
        $this->addSql('ALTER TABLE oeuvre_concours DROP FOREIGN KEY oeuvre_concours_ibfk_1');
        $this->addSql('ALTER TABLE oeuvre_concours DROP FOREIGN KEY oeuvre_concours_ibfk_2');
        $this->addSql('ALTER TABLE offreenchere DROP FOREIGN KEY offreenchere_ibfk_1');
        $this->addSql('ALTER TABLE venteencheres DROP FOREIGN KEY venteencheres_ibfk_1');
        $this->addSql('ALTER TABLE venteencheres DROP FOREIGN KEY fk_Exposition');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY vote_ibfk_3');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY vote_ibfk_1');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY vote_ibfk_2');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE concours');
        $this->addSql('DROP TABLE exposition');
        $this->addSql('DROP TABLE likes');
        $this->addSql('DROP TABLE oeuvre_concours');
        $this->addSql('DROP TABLE offreenchere');
        $this->addSql('DROP TABLE venteencheres');
        $this->addSql('DROP TABLE vote');
        $this->addSql('ALTER TABLE calendar CHANGE name name VARCHAR(255) NOT NULL, CHANGE startdate startdate DATETIME NOT NULL, CHANGE enddate enddate DATETIME NOT NULL');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY fk_panier');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY fk_panier');
        $this->addSql('ALTER TABLE commande CHANGE panier panier INT DEFAULT NULL, CHANGE date date DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D24CC0DF2 FOREIGN KEY (panier) REFERENCES panier (id)');
        $this->addSql('DROP INDEX fk_panier ON commande');
        $this->addSql('CREATE INDEX IDX_6EEAA67D24CC0DF2 ON commande (panier)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT fk_panier FOREIGN KEY (panier) REFERENCES panier (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY evenement_ibfk_1');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY evenement_ibfk_1');
        $this->addSql('ALTER TABLE evenement CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE lieu lieu VARCHAR(255) NOT NULL, CHANGE date date DATETIME NOT NULL, CHANGE price price DOUBLE PRECISION NOT NULL, CHANGE capacite capacite INT NOT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EAC56442 FOREIGN KEY (calender) REFERENCES calendar (id)');
        $this->addSql('DROP INDEX calender ON evenement');
        $this->addSql('CREATE INDEX IDX_B26681EAC56442 ON evenement (calender)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT evenement_ibfk_1 FOREIGN KEY (calender) REFERENCES calendar (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateurs_evenement MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateurs_evenement DROP FOREIGN KEY utilisateurs_evenement_ibfk_1');
        $this->addSql('ALTER TABLE utilisateurs_evenement DROP FOREIGN KEY utilisateurs_evenement_ibfk_2');
        $this->addSql('DROP INDEX `primary` ON utilisateurs_evenement');
        $this->addSql('ALTER TABLE utilisateurs_evenement DROP FOREIGN KEY utilisateurs_evenement_ibfk_1');
        $this->addSql('ALTER TABLE utilisateurs_evenement DROP FOREIGN KEY utilisateurs_evenement_ibfk_2');
        $this->addSql('ALTER TABLE utilisateurs_evenement DROP id, CHANGE id_utilisateur id_utilisateur INT NOT NULL, CHANGE id_evenement id_evenement INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD CONSTRAINT FK_F88416058B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD CONSTRAINT FK_F884160550EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD PRIMARY KEY (id_evenement, id_utilisateur)');
        $this->addSql('DROP INDEX id_evenement ON utilisateurs_evenement');
        $this->addSql('CREATE INDEX IDX_F88416058B13D439 ON utilisateurs_evenement (id_evenement)');
        $this->addSql('DROP INDEX id_utilisateur ON utilisateurs_evenement');
        $this->addSql('CREATE INDEX IDX_F884160550EAE44 ON utilisateurs_evenement (id_utilisateur)');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD CONSTRAINT utilisateurs_evenement_ibfk_1 FOREIGN KEY (id_evenement) REFERENCES evenement (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD CONSTRAINT utilisateurs_evenement_ibfk_2 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oeuvreart DROP FOREIGN KEY oeuvreart_ibfk_1');
        $this->addSql('ALTER TABLE oeuvreart DROP FOREIGN KEY oeuvreart_ibfk_2');
        $this->addSql('ALTER TABLE oeuvreart DROP FOREIGN KEY oeuvreart_ibfk_1');
        $this->addSql('ALTER TABLE oeuvreart DROP FOREIGN KEY oeuvreart_ibfk_2');
        $this->addSql('ALTER TABLE oeuvreart CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE oeuvreart ADD CONSTRAINT FK_126D5E8CC9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (idcategorie)');
        $this->addSql('ALTER TABLE oeuvreart ADD CONSTRAINT FK_126D5E8C429A9C3F FOREIGN KEY (id_artiste) REFERENCES utilisateur (id)');
        $this->addSql('DROP INDEX fk_categorie_id ON oeuvreart');
        $this->addSql('CREATE INDEX IDX_126D5E8CC9486A13 ON oeuvreart (id_categorie)');
        $this->addSql('DROP INDEX fk_id_artiste ON oeuvreart');
        $this->addSql('CREATE INDEX IDX_126D5E8C429A9C3F ON oeuvreart (id_artiste)');
        $this->addSql('ALTER TABLE oeuvreart ADD CONSTRAINT oeuvreart_ibfk_1 FOREIGN KEY (id_categorie) REFERENCES categorie (idCategorie) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oeuvreart ADD CONSTRAINT oeuvreart_ibfk_2 FOREIGN KEY (id_artiste) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY panier_ibfk_1');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY panier_ibfk_1');
        $this->addSql('ALTER TABLE panier CHANGE client client INT DEFAULT NULL, CHANGE dateAjout dateajout DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2C7440455 FOREIGN KEY (client) REFERENCES utilisateur (id)');
        $this->addSql('DROP INDEX fk_client ON panier');
        $this->addSql('CREATE INDEX IDX_24CC0DF2C7440455 ON panier (client)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT panier_ibfk_1 FOREIGN KEY (client) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panieroeuvre DROP FOREIGN KEY panieroeuvre_ibfk_1');
        $this->addSql('ALTER TABLE panieroeuvre DROP FOREIGN KEY panieroeuvre_ibfk_2');
        $this->addSql('ALTER TABLE panieroeuvre DROP FOREIGN KEY panieroeuvre_ibfk_1');
        $this->addSql('ALTER TABLE panieroeuvre DROP FOREIGN KEY panieroeuvre_ibfk_2');
        $this->addSql('ALTER TABLE panieroeuvre CHANGE id_panier id_panier INT DEFAULT NULL, CHANGE id_oeuvre id_oeuvre INT DEFAULT NULL');
        $this->addSql('ALTER TABLE panieroeuvre ADD CONSTRAINT FK_96EBEDDF13C99B13 FOREIGN KEY (id_oeuvre) REFERENCES oeuvreart (idoeuvreart)');
        $this->addSql('ALTER TABLE panieroeuvre ADD CONSTRAINT FK_96EBEDDF2FBB81F FOREIGN KEY (id_panier) REFERENCES panier (id)');
        $this->addSql('DROP INDEX fk-id_oeuvre ON panieroeuvre');
        $this->addSql('CREATE INDEX IDX_96EBEDDF13C99B13 ON panieroeuvre (id_oeuvre)');
        $this->addSql('DROP INDEX fk-id_panier ON panieroeuvre');
        $this->addSql('CREATE INDEX IDX_96EBEDDF2FBB81F ON panieroeuvre (id_panier)');
        $this->addSql('ALTER TABLE panieroeuvre ADD CONSTRAINT panieroeuvre_ibfk_1 FOREIGN KEY (id_oeuvre) REFERENCES oeuvreart (idOeuvreArt) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panieroeuvre ADD CONSTRAINT panieroeuvre_ibfk_2 FOREIGN KEY (id_panier) REFERENCES panier (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX login ON utilisateur');
        $this->addSql('DROP INDEX email ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE numTel numtel INT NOT NULL, CHANGE login login VARCHAR(255) NOT NULL, CHANGE cin cin INT NOT NULL, CHANGE mdp mdp VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE genre genre VARCHAR(255) NOT NULL, CHANGE dateNaissance datenaissance DATE NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, oeuvre_id INT DEFAULT NULL, commentaire TEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, date_commentaire DATE DEFAULT NULL, INDEX fk_comment_client (client_id), INDEX fk_comment_oeuvre (oeuvre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE concours (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(11) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_debut DATE NOT NULL, date_fin DATE NOT NULL, description VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE exposition (id INT AUTO_INCREMENT NOT NULL, id_utilisateur INT DEFAULT NULL, nom VARCHAR(55) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, dateDebut DATE NOT NULL, dateFin DATE NOT NULL, nbrOeuvre INT NOT NULL, INDEX id_utilisateur (id_utilisateur), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE likes (idLike INT AUTO_INCREMENT NOT NULL, idUtilisateur INT DEFAULT NULL, idOeuvreArt INT DEFAULT NULL, estLike TINYINT(1) DEFAULT NULL, INDEX idUtilisateur (idUtilisateur), INDEX idOeuvreArt (idOeuvreArt), PRIMARY KEY(idLike)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE oeuvre_concours (id INT AUTO_INCREMENT NOT NULL, id_oeuvre INT NOT NULL, id_concours INT NOT NULL, INDEX id_concours (id_concours), INDEX id_oeuvre (id_oeuvre), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE offreenchere (id INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, date DATE NOT NULL, id_VenteEnchere INT DEFAULT NULL, id_utilisateur INT DEFAULT NULL, INDEX fk_VenteEnchere (id_VenteEnchere), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE venteencheres (id INT AUTO_INCREMENT NOT NULL, id_exposition INT DEFAULT NULL, id_utilisateur INT NOT NULL, dateDebut DATE NOT NULL, dateFin DATE NOT NULL, prixDepart DOUBLE PRECISION NOT NULL, statue VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_Exposition (id_exposition), INDEX id_utilisateur (id_utilisateur), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, concours INT NOT NULL, user INT NOT NULL, oeuvre INT DEFAULT NULL, note INT NOT NULL, INDEX fk_concours (concours), INDEX fk_user (user), INDEX fk_oeuvres (oeuvre), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT commentaire_ibfk_1 FOREIGN KEY (oeuvre_id) REFERENCES oeuvreart (idOeuvreArt)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT kfff FOREIGN KEY (client_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE exposition ADD CONSTRAINT exposition_ibfk_1 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT likes_ibfk_2 FOREIGN KEY (idUtilisateur) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT likes_ibfk_1 FOREIGN KEY (idOeuvreArt) REFERENCES oeuvreart (idOeuvreArt) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oeuvre_concours ADD CONSTRAINT oeuvre_concours_ibfk_1 FOREIGN KEY (id_concours) REFERENCES concours (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oeuvre_concours ADD CONSTRAINT oeuvre_concours_ibfk_2 FOREIGN KEY (id_oeuvre) REFERENCES oeuvreart (idOeuvreArt)');
        $this->addSql('ALTER TABLE offreenchere ADD CONSTRAINT offreenchere_ibfk_1 FOREIGN KEY (id_VenteEnchere) REFERENCES venteencheres (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE venteencheres ADD CONSTRAINT venteencheres_ibfk_1 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE venteencheres ADD CONSTRAINT fk_Exposition FOREIGN KEY (id_exposition) REFERENCES exposition (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT vote_ibfk_3 FOREIGN KEY (user) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT vote_ibfk_1 FOREIGN KEY (oeuvre) REFERENCES oeuvreart (idOeuvreArt) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT vote_ibfk_2 FOREIGN KEY (concours) REFERENCES concours (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F6EEAA67D');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE calendar CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE startdate startdate DATE DEFAULT NULL, CHANGE enddate enddate DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D24CC0DF2');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D24CC0DF2');
        $this->addSql('ALTER TABLE commande CHANGE panier panier INT NOT NULL, CHANGE date date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT fk_panier FOREIGN KEY (panier) REFERENCES panier (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_6eeaa67d24cc0df2 ON commande');
        $this->addSql('CREATE INDEX fk_panier ON commande (panier)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D24CC0DF2 FOREIGN KEY (panier) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EAC56442');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EAC56442');
        $this->addSql('ALTER TABLE evenement CHANGE nom nom VARCHAR(255) DEFAULT NULL, CHANGE lieu lieu VARCHAR(255) DEFAULT NULL, CHANGE date date DATE DEFAULT NULL, CHANGE price price DOUBLE PRECISION DEFAULT NULL, CHANGE capacite capacite INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT evenement_ibfk_1 FOREIGN KEY (calender) REFERENCES calendar (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_b26681eac56442 ON evenement');
        $this->addSql('CREATE INDEX calender ON evenement (calender)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EAC56442 FOREIGN KEY (calender) REFERENCES calendar (id)');
        $this->addSql('ALTER TABLE oeuvreart DROP FOREIGN KEY FK_126D5E8CC9486A13');
        $this->addSql('ALTER TABLE oeuvreart DROP FOREIGN KEY FK_126D5E8C429A9C3F');
        $this->addSql('ALTER TABLE oeuvreart DROP FOREIGN KEY FK_126D5E8CC9486A13');
        $this->addSql('ALTER TABLE oeuvreart DROP FOREIGN KEY FK_126D5E8C429A9C3F');
        $this->addSql('ALTER TABLE oeuvreart CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE oeuvreart ADD CONSTRAINT oeuvreart_ibfk_1 FOREIGN KEY (id_categorie) REFERENCES categorie (idCategorie) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE oeuvreart ADD CONSTRAINT oeuvreart_ibfk_2 FOREIGN KEY (id_artiste) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_126d5e8cc9486a13 ON oeuvreart');
        $this->addSql('CREATE INDEX fk_categorie_id ON oeuvreart (id_categorie)');
        $this->addSql('DROP INDEX idx_126d5e8c429a9c3f ON oeuvreart');
        $this->addSql('CREATE INDEX fk_id_artiste ON oeuvreart (id_artiste)');
        $this->addSql('ALTER TABLE oeuvreart ADD CONSTRAINT FK_126D5E8CC9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (idcategorie)');
        $this->addSql('ALTER TABLE oeuvreart ADD CONSTRAINT FK_126D5E8C429A9C3F FOREIGN KEY (id_artiste) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2C7440455');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2C7440455');
        $this->addSql('ALTER TABLE panier CHANGE client client INT NOT NULL, CHANGE dateajout dateAjout DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT panier_ibfk_1 FOREIGN KEY (client) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_24cc0df2c7440455 ON panier');
        $this->addSql('CREATE INDEX fk_client ON panier (client)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2C7440455 FOREIGN KEY (client) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE panieroeuvre DROP FOREIGN KEY FK_96EBEDDF13C99B13');
        $this->addSql('ALTER TABLE panieroeuvre DROP FOREIGN KEY FK_96EBEDDF2FBB81F');
        $this->addSql('ALTER TABLE panieroeuvre DROP FOREIGN KEY FK_96EBEDDF13C99B13');
        $this->addSql('ALTER TABLE panieroeuvre DROP FOREIGN KEY FK_96EBEDDF2FBB81F');
        $this->addSql('ALTER TABLE panieroeuvre CHANGE id_oeuvre id_oeuvre INT NOT NULL, CHANGE id_panier id_panier INT NOT NULL');
        $this->addSql('ALTER TABLE panieroeuvre ADD CONSTRAINT panieroeuvre_ibfk_1 FOREIGN KEY (id_oeuvre) REFERENCES oeuvreart (idOeuvreArt) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE panieroeuvre ADD CONSTRAINT panieroeuvre_ibfk_2 FOREIGN KEY (id_panier) REFERENCES panier (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_96ebeddf2fbb81f ON panieroeuvre');
        $this->addSql('CREATE INDEX fk-id_panier ON panieroeuvre (id_panier)');
        $this->addSql('DROP INDEX idx_96ebeddf13c99b13 ON panieroeuvre');
        $this->addSql('CREATE INDEX fk-id_oeuvre ON panieroeuvre (id_oeuvre)');
        $this->addSql('ALTER TABLE panieroeuvre ADD CONSTRAINT FK_96EBEDDF13C99B13 FOREIGN KEY (id_oeuvre) REFERENCES oeuvreart (idoeuvreart)');
        $this->addSql('ALTER TABLE panieroeuvre ADD CONSTRAINT FK_96EBEDDF2FBB81F FOREIGN KEY (id_panier) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE utilisateur CHANGE nom nom TEXT NOT NULL, CHANGE prenom prenom TEXT NOT NULL, CHANGE email email VARCHAR(100) NOT NULL, CHANGE numtel numTel INT DEFAULT NULL, CHANGE login login VARCHAR(100) NOT NULL, CHANGE cin cin INT DEFAULT NULL, CHANGE mdp mdp TEXT NOT NULL, CHANGE image image TEXT DEFAULT NULL, CHANGE genre genre TEXT DEFAULT NULL, CHANGE datenaissance dateNaissance DATE DEFAULT NULL, CHANGE adresse adresse TEXT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX login ON utilisateur (login)');
        $this->addSql('CREATE UNIQUE INDEX email ON utilisateur (email)');
        $this->addSql('ALTER TABLE utilisateurs_evenement DROP FOREIGN KEY FK_F88416058B13D439');
        $this->addSql('ALTER TABLE utilisateurs_evenement DROP FOREIGN KEY FK_F884160550EAE44');
        $this->addSql('ALTER TABLE utilisateurs_evenement DROP FOREIGN KEY FK_F88416058B13D439');
        $this->addSql('ALTER TABLE utilisateurs_evenement DROP FOREIGN KEY FK_F884160550EAE44');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD id INT AUTO_INCREMENT NOT NULL, CHANGE id_evenement id_evenement INT DEFAULT NULL, CHANGE id_utilisateur id_utilisateur INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD CONSTRAINT utilisateurs_evenement_ibfk_1 FOREIGN KEY (id_evenement) REFERENCES evenement (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD CONSTRAINT utilisateurs_evenement_ibfk_2 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_f884160550eae44 ON utilisateurs_evenement');
        $this->addSql('CREATE INDEX id_utilisateur ON utilisateurs_evenement (id_utilisateur)');
        $this->addSql('DROP INDEX idx_f88416058b13d439 ON utilisateurs_evenement');
        $this->addSql('CREATE INDEX id_evenement ON utilisateurs_evenement (id_evenement)');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD CONSTRAINT FK_F88416058B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE utilisateurs_evenement ADD CONSTRAINT FK_F884160550EAE44 FOREIGN KEY (id_utilisateur) REFERENCES utilisateur (id)');
    }
}
