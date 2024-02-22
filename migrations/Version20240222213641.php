<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240222213641 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication ADD topic_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C67791F55203D FOREIGN KEY (topic_id) REFERENCES topic (id)');
        $this->addSql('CREATE INDEX IDX_AF3C67791F55203D ON publication (topic_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C67791F55203D');
        $this->addSql('DROP INDEX IDX_AF3C67791F55203D ON publication');
        $this->addSql('ALTER TABLE publication DROP topic_id');
    }
}
