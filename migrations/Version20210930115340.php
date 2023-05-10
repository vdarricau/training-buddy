<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210930115340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove Variation table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE component DROP CONSTRAINT fk_49fea1575182bfd8');
        $this->addSql('DROP SEQUENCE variation_id_seq CASCADE');
        $this->addSql('DROP TABLE variation');
        $this->addSql('DROP INDEX idx_49fea1575182bfd8');
        $this->addSql('ALTER TABLE component DROP variation_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE variation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE variation (id INT NOT NULL, exercise_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_629b33eae934951a ON variation (exercise_id)');
        $this->addSql('ALTER TABLE variation ADD CONSTRAINT fk_629b33eae934951a FOREIGN KEY (exercise_id) REFERENCES exercise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE component ADD variation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT fk_49fea1575182bfd8 FOREIGN KEY (variation_id) REFERENCES variation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_49fea1575182bfd8 ON component (variation_id)');
    }
}
