<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241125100852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instrument ADD type_instrument_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE instrument ADD CONSTRAINT FK_3CBF69DD7C1CAAA9 FOREIGN KEY (type_instrument_id) REFERENCES type_instrument (id)');
        $this->addSql('CREATE INDEX IDX_3CBF69DD7C1CAAA9 ON instrument (type_instrument_id)');
        $this->addSql('ALTER TABLE type_instrument DROP FOREIGN KEY FK_21BCBFF8CF11D9C');
        $this->addSql('DROP INDEX IDX_21BCBFF8CF11D9C ON type_instrument');
        $this->addSql('ALTER TABLE type_instrument DROP instrument_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instrument DROP FOREIGN KEY FK_3CBF69DD7C1CAAA9');
        $this->addSql('DROP INDEX IDX_3CBF69DD7C1CAAA9 ON instrument');
        $this->addSql('ALTER TABLE instrument DROP type_instrument_id');
        $this->addSql('ALTER TABLE type_instrument ADD instrument_id INT NOT NULL');
        $this->addSql('ALTER TABLE type_instrument ADD CONSTRAINT FK_21BCBFF8CF11D9C FOREIGN KEY (instrument_id) REFERENCES instrument (id)');
        $this->addSql('CREATE INDEX IDX_21BCBFF8CF11D9C ON type_instrument (instrument_id)');
    }
}
