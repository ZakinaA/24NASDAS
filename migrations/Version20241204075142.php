<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241204075142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE couleur (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE marque (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modele (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE instrument ADD modele_id INT DEFAULT NULL, ADD marque_id INT DEFAULT NULL, ADD couleur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE instrument ADD CONSTRAINT FK_3CBF69DDAC14B70A FOREIGN KEY (modele_id) REFERENCES modele (id)');
        $this->addSql('ALTER TABLE instrument ADD CONSTRAINT FK_3CBF69DD4827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)');
        $this->addSql('ALTER TABLE instrument ADD CONSTRAINT FK_3CBF69DDC31BA576 FOREIGN KEY (couleur_id) REFERENCES couleur (id)');
        $this->addSql('CREATE INDEX IDX_3CBF69DDAC14B70A ON instrument (modele_id)');
        $this->addSql('CREATE INDEX IDX_3CBF69DD4827B9B2 ON instrument (marque_id)');
        $this->addSql('CREATE INDEX IDX_3CBF69DDC31BA576 ON instrument (couleur_id)');
        $this->addSql('ALTER TABLE professeur ADD type_instrument_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE professeur ADD CONSTRAINT FK_17A552997C1CAAA9 FOREIGN KEY (type_instrument_id) REFERENCES type_instrument (id)');
        $this->addSql('CREATE INDEX IDX_17A552997C1CAAA9 ON professeur (type_instrument_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instrument DROP FOREIGN KEY FK_3CBF69DDC31BA576');
        $this->addSql('ALTER TABLE instrument DROP FOREIGN KEY FK_3CBF69DD4827B9B2');
        $this->addSql('ALTER TABLE instrument DROP FOREIGN KEY FK_3CBF69DDAC14B70A');
        $this->addSql('DROP TABLE couleur');
        $this->addSql('DROP TABLE marque');
        $this->addSql('DROP TABLE modele');
        $this->addSql('DROP INDEX IDX_3CBF69DDAC14B70A ON instrument');
        $this->addSql('DROP INDEX IDX_3CBF69DD4827B9B2 ON instrument');
        $this->addSql('DROP INDEX IDX_3CBF69DDC31BA576 ON instrument');
        $this->addSql('ALTER TABLE instrument DROP modele_id, DROP marque_id, DROP couleur_id');
        $this->addSql('ALTER TABLE professeur DROP FOREIGN KEY FK_17A552997C1CAAA9');
        $this->addSql('DROP INDEX IDX_17A552997C1CAAA9 ON professeur');
        $this->addSql('ALTER TABLE professeur DROP type_instrument_id');
    }
}
