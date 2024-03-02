<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302143453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE docteur CHANGE specialite specialite VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD name VARCHAR(255) NOT NULL, ADD address VARCHAR(255) NOT NULL');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE docteur CHANGE specialite specialite VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation DROP name, DROP address, CHANGE id_user id_user VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL, CHANGE doctor_id doctor_id INT NOT NULL');
    }
}
