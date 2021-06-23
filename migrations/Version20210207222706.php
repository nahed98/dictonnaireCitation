<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210207222706 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX nom ON auteur');
        $this->addSql('DROP INDEX nom_2 ON auteur');
        $this->addSql('ALTER TABLE editeur ADD email VARCHAR(255) NOT NULL, ADD username VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE message CHANGE numero numero VARCHAR(8) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX nom ON auteur (nom, prenom)');
        $this->addSql('CREATE UNIQUE INDEX nom_2 ON auteur (nom, prenom)');
        $this->addSql('ALTER TABLE editeur DROP email, DROP username');
        $this->addSql('ALTER TABLE message CHANGE numero numero INT NOT NULL');
    }
}
