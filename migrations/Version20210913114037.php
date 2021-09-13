<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210913114037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Admin user.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'EOD'
INSERT INTO user_account (id, firstname, lastname, email, roles, password, is_verified) 
VALUES (1, 'Admin', 'Admin', 'admin@trainingbuddy.com', '["ROLE_ADMIN"]', '$2y$13$GvmhuuX66ncAkuD0fBW9hO/0fYofcJ06di2hg9gpFOj7DuizdTlk.', true)
EOD
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM user_account WHERE email = 'admin@trainingbuddy.com'");
    }
}
