<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241202100330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instrument ADD modele_id INT DEFAULT NULL, ADD marque_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE instrument ADD CONSTRAINT FK_3CBF69DDAC14B70A FOREIGN KEY (modele_id) REFERENCES modele (id)');
        $this->addSql('ALTER TABLE instrument ADD CONSTRAINT FK_3CBF69DD4827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)');
        $this->addSql('CREATE INDEX IDX_3CBF69DDAC14B70A ON instrument (modele_id)');
        $this->addSql('CREATE INDEX IDX_3CBF69DD4827B9B2 ON instrument (marque_id)');
        $this->addSql('ALTER TABLE marque DROP FOREIGN KEY FK_5A6F91CECF11D9C');
        $this->addSql('DROP INDEX IDX_5A6F91CECF11D9C ON marque');
        $this->addSql('ALTER TABLE marque DROP instrument_id');
        $this->addSql('ALTER TABLE modele DROP FOREIGN KEY FK_10028558CF11D9C');
        $this->addSql('DROP INDEX IDX_10028558CF11D9C ON modele');
        $this->addSql('ALTER TABLE modele DROP instrument_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instrument DROP FOREIGN KEY FK_3CBF69DDAC14B70A');
        $this->addSql('ALTER TABLE instrument DROP FOREIGN KEY FK_3CBF69DD4827B9B2');
        $this->addSql('DROP INDEX IDX_3CBF69DDAC14B70A ON instrument');
        $this->addSql('DROP INDEX IDX_3CBF69DD4827B9B2 ON instrument');
        $this->addSql('ALTER TABLE instrument DROP modele_id, DROP marque_id');
        $this->addSql('ALTER TABLE marque ADD instrument_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE marque ADD CONSTRAINT FK_5A6F91CECF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('CREATE INDEX IDX_5A6F91CECF11D9C ON marque (instrument_id)');
        $this->addSql('ALTER TABLE modele ADD instrument_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE modele ADD CONSTRAINT FK_10028558CF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('CREATE INDEX IDX_10028558CF11D9C ON modele (instrument_id)');
    }
}
