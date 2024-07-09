<?php

declare(strict_types=1);

namespace App\Framework\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240707192936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create a table for studios';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            sql: 'CREATE TABLE studios (
                id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\',
                # Name UNIQUE
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                street VARCHAR(255) NOT NULL,
                city VARCHAR(255) NOT NULL,
                zip_code VARCHAR(255) NOT NULL,
                country VARCHAR(255) NOT NULL,
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\' DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\' DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );

        $this->addSql(
            sql: 'CREATE UNIQUE INDEX UNIQ_8C9F6D5E5E237E06 ON studios (name)'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_8C9F6D5E5E237E06 ON studios');
        $this->addSql('DROP TABLE studios');
    }
}
