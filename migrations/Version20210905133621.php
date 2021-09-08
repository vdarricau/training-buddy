<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210905133621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE component DROP CONSTRAINT fk_49fea157e934951a');
        $this->addSql('DROP INDEX idx_49fea157e934951a');
        $this->addSql('ALTER TABLE component ADD variation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE component DROP exercise_id');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT FK_49FEA1575182BFD8 FOREIGN KEY (variation_id) REFERENCES variation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_49FEA1575182BFD8 ON component (variation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE component DROP CONSTRAINT FK_49FEA1575182BFD8');
        $this->addSql('DROP INDEX IDX_49FEA1575182BFD8');
        $this->addSql('ALTER TABLE component ADD exercise_id INT NOT NULL');
        $this->addSql('ALTER TABLE component DROP variation_id');
        $this->addSql('ALTER TABLE component ADD CONSTRAINT fk_49fea157e934951a FOREIGN KEY (exercise_id) REFERENCES exercise (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_49fea157e934951a ON component (exercise_id)');
    }
}
