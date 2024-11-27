<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241127102631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE responsable ADD compte_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE responsable ADD CONSTRAINT FK_52520D07F2C56620 FOREIGN KEY (compte_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_52520D07F2C56620 ON responsable (compte_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE responsable DROP FOREIGN KEY FK_52520D07F2C56620');
        $this->addSql('DROP INDEX UNIQ_52520D07F2C56620 ON responsable');
        $this->addSql('ALTER TABLE responsable DROP compte_id');
    }
}
