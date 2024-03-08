<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307222030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        
       
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation_patient ADD reservation_date_id INT DEFAULT NULL, DROP date_reservation');
        $this->addSql('ALTER TABLE consultation_patient ADD CONSTRAINT FK_5FD92609DF028DEE FOREIGN KEY (reservation_date_id) REFERENCES reservation (id)');
        $this->addSql('CREATE INDEX IDX_5FD92609DF028DEE ON consultation_patient (reservation_date_id)');
    }
}
