<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210905131519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE component_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE component (id INT NOT NULL, workout_id INT NOT NULL, exercise_id INT NOT NULL, name VARCHAR(255) NOT NULL, set_and_rep TEXT DEFAULT NULL, order_number INT NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_49FEA157A6CCCFC9 ON component (workout_id)');
        $this->addSql('CREATE INDEX IDX_49FEA157E934951A ON component (exercise_id)');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA157A6CCCFC9 FOREIGN KEY (workout_id) REFERENCES workout (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA157E934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE component_id_seq CASCADE');
        $this->addSql('DROP TABLE component');
    }
}
