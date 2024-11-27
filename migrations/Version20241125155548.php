<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241125155548 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE instrument_accessoire (instrument_id INT NOT NULL, accessoire_id INT NOT NULL, INDEX IDX_C730316ACF11D9C (instrument_id), INDEX IDX_C730316AD23B67ED (accessoire_id), PRIMARY KEY(instrument_id, accessoire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE instrument_accessoire ADD CONSTRAINT FK_C730316ACF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE instrument_accessoire ADD CONSTRAINT FK_C730316AD23B67ED FOREIGN KEY (accessoire_id) REFERENCES accessoire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE instrument DROP FOREIGN KEY FK_3CBF69DDD23B67ED');
        $this->addSql('DROP INDEX IDX_3CBF69DDD23B67ED ON instrument');
        $this->addSql('ALTER TABLE instrument DROP accessoire_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instrument_accessoire DROP FOREIGN KEY FK_C730316ACF11D9C');
        $this->addSql('ALTER TABLE instrument_accessoire DROP FOREIGN KEY FK_C730316AD23B67ED');
        $this->addSql('DROP TABLE instrument_accessoire');
        $this->addSql('ALTER TABLE instrument ADD accessoire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE instrument ADD CONSTRAINT FK_3CBF69DDD23B67ED FOREIGN KEY (accessoire_id) REFERENCES accessoire (id)');
        $this->addSql('CREATE INDEX IDX_3CBF69DDD23B67ED ON instrument (accessoire_id)');
    }
}
