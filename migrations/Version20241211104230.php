<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241211104230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, inscription_id INT DEFAULT NULL, montant INT NOT NULL, date_paiement DATE NOT NULL, INDEX IDX_B1DC7A1E5DAC5993 (inscription_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quotient_familial (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, quotient_mini INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tarif (id INT AUTO_INCREMENT NOT NULL, type_cours_id INT DEFAULT NULL, quotient_familial_id INT DEFAULT NULL, montant INT NOT NULL, INDEX IDX_E7189C9B3305F4C (type_cours_id), INDEX IDX_E7189C9C8D8BF3D (quotient_familial_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E5DAC5993 FOREIGN KEY (inscription_id) REFERENCES inscription (id)');
        $this->addSql('ALTER TABLE tarif ADD CONSTRAINT FK_E7189C9B3305F4C FOREIGN KEY (type_cours_id) REFERENCES type_cours (id)');
        $this->addSql('ALTER TABLE tarif ADD CONSTRAINT FK_E7189C9C8D8BF3D FOREIGN KEY (quotient_familial_id) REFERENCES quotient_familial (id)');
        $this->addSql('ALTER TABLE responsable ADD quotient_familial_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE responsable ADD CONSTRAINT FK_52520D07C8D8BF3D FOREIGN KEY (quotient_familial_id) REFERENCES quotient_familial (id)');
        $this->addSql('CREATE INDEX IDX_52520D07C8D8BF3D ON responsable (quotient_familial_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE responsable DROP FOREIGN KEY FK_52520D07C8D8BF3D');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E5DAC5993');
        $this->addSql('ALTER TABLE tarif DROP FOREIGN KEY FK_E7189C9B3305F4C');
        $this->addSql('ALTER TABLE tarif DROP FOREIGN KEY FK_E7189C9C8D8BF3D');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE quotient_familial');
        $this->addSql('DROP TABLE tarif');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('DROP INDEX IDX_52520D07C8D8BF3D ON responsable');
        $this->addSql('ALTER TABLE responsable DROP quotient_familial_id');
    }
}
