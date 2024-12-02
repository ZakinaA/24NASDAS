<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241202124755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE professeur ADD type_instrument_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE professeur ADD CONSTRAINT FK_17A552997C1CAAA9 FOREIGN KEY (type_instrument_id) REFERENCES type_instrument (id)');
        $this->addSql('CREATE INDEX IDX_17A552997C1CAAA9 ON professeur (type_instrument_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE professeur DROP FOREIGN KEY FK_17A552997C1CAAA9');
        $this->addSql('DROP INDEX IDX_17A552997C1CAAA9 ON professeur');
        $this->addSql('ALTER TABLE professeur DROP type_instrument_id');
    }
}
