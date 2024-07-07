<?php

declare(strict_types=1);

namespace App\Framework\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240707195410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create the table for rooms';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            sql: 'CREATE TABLE rooms (
                id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
                studio_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
                name VARCHAR(255) NOT NULL,
                capacity INT UNSIGNED NOT NULL,
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\' DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\' DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY(id),
                CONSTRAINT FK_1F1B251F8D60D6A6 FOREIGN KEY (studio_id) REFERENCES studios (id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE rooms');
    }
}
