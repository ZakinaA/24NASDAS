<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241127080517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contrat_pret (id INT AUTO_INCREMENT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, description LONGTEXT NOT NULL, prix DOUBLE PRECISION NOT NULL, quotite DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsable (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, num_rue INT NOT NULL, rue VARCHAR(255) NOT NULL, copos INT NOT NULL, ville VARCHAR(255) NOT NULL, tel INT NOT NULL, mail VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE eleve ADD responsable_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F753C59D72 FOREIGN KEY (responsable_id) REFERENCES responsable (id)');
        $this->addSql('CREATE INDEX IDX_ECA105F753C59D72 ON eleve (responsable_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F753C59D72');
        $this->addSql('DROP TABLE contrat_pret');
        $this->addSql('DROP TABLE responsable');
        $this->addSql('DROP INDEX IDX_ECA105F753C59D72 ON eleve');
        $this->addSql('ALTER TABLE eleve DROP responsable_id');
    }
}