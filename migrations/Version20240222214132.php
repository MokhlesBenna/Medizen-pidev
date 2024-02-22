<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240222214132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sponseur ADD event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sponseur ADD CONSTRAINT FK_AFE0768871F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_AFE0768871F7E88B ON sponseur (event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sponseur DROP FOREIGN KEY FK_AFE0768871F7E88B');
        $this->addSql('DROP INDEX IDX_AFE0768871F7E88B ON sponseur');
        $this->addSql('ALTER TABLE sponseur DROP event_id');
    }
}
