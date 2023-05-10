<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210930120457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE component ADD exercise_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA157E934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_49FEA157E934951A ON component (exercise_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE component DROP CONSTRAINT FK_49FEA157E934951A');
        $this->addSql('DROP INDEX IDX_49FEA157E934951A');
        $this->addSql('ALTER TABLE component DROP exercise_id');
    }
}
