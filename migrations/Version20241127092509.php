<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241127092509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instrument DROP FOREIGN KEY FK_3CBF69DD8EAE3863');
        $this->addSql('DROP INDEX IDX_3CBF69DD8EAE3863 ON instrument');
        $this->addSql('ALTER TABLE instrument DROP intervention_id');
        $this->addSql('ALTER TABLE intervention ADD instrument_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814ABCF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('CREATE INDEX IDX_D11814ABCF11D9C ON intervention (instrument_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instrument ADD intervention_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE instrument ADD CONSTRAINT FK_3CBF69DD8EAE3863 FOREIGN KEY (intervention_id) REFERENCES intervention (id)');
        $this->addSql('CREATE INDEX IDX_3CBF69DD8EAE3863 ON instrument (intervention_id)');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814ABCF11D9C');
        $this->addSql('DROP INDEX IDX_D11814ABCF11D9C ON intervention');
        $this->addSql('ALTER TABLE intervention DROP instrument_id');
    }
}
