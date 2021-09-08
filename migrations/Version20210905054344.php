<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210905054344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add variation';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE variation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE variation (id INT NOT NULL, exercice_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_629B33EA89D40298 ON variation (exercice_id)');
        $this->addSql('ALTER TABLE variation ADD CONSTRAINT FK_629B33EA89D40298 FOREIGN KEY (exercice_id) REFERENCES exercise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE exercise DROP media_url');
        $this->addSql('ALTER TABLE exercise ALTER description TYPE TEXT');
        $this->addSql('ALTER TABLE exercise ALTER description DROP DEFAULT');
        $this->addSql('ALTER TABLE exercise ALTER description DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE variation_id_seq CASCADE');
        $this->addSql('DROP TABLE variation');
        $this->addSql('ALTER TABLE exercise ADD media_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE exercise ALTER description TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE exercise ALTER description DROP DEFAULT');
        $this->addSql('ALTER TABLE exercise ALTER description SET NOT NULL');
    }
}
