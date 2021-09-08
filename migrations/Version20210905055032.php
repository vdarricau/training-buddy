<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210905055032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'rename exercice to exercise';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE variation DROP CONSTRAINT fk_629b33ea89d40298');
        $this->addSql('DROP INDEX idx_629b33ea89d40298');
        $this->addSql('ALTER TABLE variation RENAME COLUMN exercice_id TO exercise_id');
        $this->addSql('ALTER TABLE variation ADD CONSTRAINT FK_629B33EAE934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_629B33EAE934951A ON variation (exercise_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE variation DROP CONSTRAINT FK_629B33EAE934951A');
        $this->addSql('DROP INDEX IDX_629B33EAE934951A');
        $this->addSql('ALTER TABLE variation RENAME COLUMN exercise_id TO exercice_id');
        $this->addSql('ALTER TABLE variation ADD CONSTRAINT fk_629b33ea89d40298 FOREIGN KEY (exercice_id) REFERENCES exercise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_629b33ea89d40298 ON variation (exercice_id)');
    }
}
