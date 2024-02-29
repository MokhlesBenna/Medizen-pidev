<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228212859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_sponseur (event_id INT NOT NULL, sponseur_id INT NOT NULL, INDEX IDX_E90C9F5871F7E88B (event_id), INDEX IDX_E90C9F585008B3AF (sponseur_id), PRIMARY KEY(event_id, sponseur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_sponseur ADD CONSTRAINT FK_E90C9F5871F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_sponseur ADD CONSTRAINT FK_E90C9F585008B3AF FOREIGN KEY (sponseur_id) REFERENCES sponseur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_sponseur DROP FOREIGN KEY FK_E90C9F5871F7E88B');
        $this->addSql('ALTER TABLE event_sponseur DROP FOREIGN KEY FK_E90C9F585008B3AF');
        $this->addSql('DROP TABLE event_sponseur');
    }
}
