<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210913123304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename user owning workout to client';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE workout DROP CONSTRAINT fk_649ffb72a76ed395');
        $this->addSql('DROP INDEX idx_649ffb72a76ed395');
        $this->addSql('ALTER TABLE workout RENAME COLUMN user_id TO client_id');
        $this->addSql('ALTER TABLE workout ADD CONSTRAINT FK_649FFB7219EB6921 FOREIGN KEY (client_id) REFERENCES user_account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_649FFB7219EB6921 ON workout (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE workout DROP CONSTRAINT FK_649FFB7219EB6921');
        $this->addSql('DROP INDEX IDX_649FFB7219EB6921');
        $this->addSql('ALTER TABLE workout RENAME COLUMN client_id TO user_id');
        $this->addSql('ALTER TABLE workout ADD CONSTRAINT fk_649ffb72a76ed395 FOREIGN KEY (user_id) REFERENCES user_account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_649ffb72a76ed395 ON workout (user_id)');
    }
}
