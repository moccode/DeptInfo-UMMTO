<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210505012343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des relations entre la table classe_de_cours et la table promotion';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classe_de_cours ADD promotion_id INT NOT NULL');
        $this->addSql('ALTER TABLE classe_de_cours ADD CONSTRAINT FK_14F31B67139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('CREATE INDEX IDX_14F31B67139DF194 ON classe_de_cours (promotion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classe_de_cours DROP FOREIGN KEY FK_14F31B67139DF194');
        $this->addSql('DROP INDEX IDX_14F31B67139DF194 ON classe_de_cours');
        $this->addSql('ALTER TABLE classe_de_cours DROP promotion_id');
    }
}
