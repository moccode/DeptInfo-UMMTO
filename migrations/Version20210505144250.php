<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210505144250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création de la table cours';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, classe_de_cours_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, date_modification DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_FDCA8C9CA65B70BD (classe_de_cours_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CA65B70BD FOREIGN KEY (classe_de_cours_id) REFERENCES classe_de_cours (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cours');
    }
}
