<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190707171331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE car_unavailability (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, car_id INTEGER DEFAULT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_C27998F1C3C6F69F ON car_unavailability (car_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE car_unavailability');
    }
}
