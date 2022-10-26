<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221023111159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users ADD lastname VARCHAR(100) NOT NULL, ADD name VARCHAR(100) NOT NULL, ADD country VARCHAR(100) NOT NULL, ADD phone VARCHAR(150) NOT NULL, DROP nom, DROP prenom, DROP ville, DROP pays, CHANGE rue address VARCHAR(255) NOT NULL, CHANGE codepostal zipcode VARCHAR(5) NOT NULL, CHANGE tel city VARCHAR(150) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users ADD nom VARCHAR(100) NOT NULL, ADD prenom VARCHAR(100) NOT NULL, ADD ville VARCHAR(100) NOT NULL, ADD pays VARCHAR(80) NOT NULL, ADD tel VARCHAR(150) NOT NULL, DROP lastname, DROP name, DROP country, DROP city, DROP phone, CHANGE address rue VARCHAR(255) NOT NULL, CHANGE zipcode codepostal VARCHAR(5) NOT NULL');
    }
}
