<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505233524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contenu_panier DROP CONSTRAINT fk_80507dc0f77d927c');
        $this->addSql('DROP INDEX idx_80507dc0f77d927c');
        $this->addSql('ALTER TABLE contenu_panier DROP panier_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE contenu_panier ADD panier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contenu_panier ADD CONSTRAINT fk_80507dc0f77d927c FOREIGN KEY (panier_id) REFERENCES panier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_80507dc0f77d927c ON contenu_panier (panier_id)');
    }
}
