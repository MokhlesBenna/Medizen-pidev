<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306121058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, totalprice DOUBLE PRECISION NOT NULL, quantity_ordered INT NOT NULL, date_ordered DATE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, etablissement_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, chef_departement VARCHAR(255) DEFAULT NULL, services_offerts VARCHAR(255) DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, INDEX IDX_C1765B63FF631228 (etablissement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etablissement (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, location VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, date_debut DATETIME DEFAULT NULL, date_fin DATETIME DEFAULT NULL, lieu VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_sponseur (event_id INT NOT NULL, sponseur_id INT NOT NULL, INDEX IDX_E90C9F5871F7E88B (event_id), INDEX IDX_E90C9F585008B3AF (sponseur_id), PRIMARY KEY(event_id, sponseur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicament (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_9A9C723A82EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sponseur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, numero INT NOT NULL, logo VARCHAR(255) NOT NULL, pack VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, username VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, date_de_naissance DATE DEFAULT NULL, genre TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_C1765B63FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
        $this->addSql('ALTER TABLE event_sponseur ADD CONSTRAINT FK_E90C9F5871F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_sponseur ADD CONSTRAINT FK_E90C9F585008B3AF FOREIGN KEY (sponseur_id) REFERENCES sponseur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medicament ADD CONSTRAINT FK_9A9C723A82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE departement DROP FOREIGN KEY FK_C1765B63FF631228');
        $this->addSql('ALTER TABLE event_sponseur DROP FOREIGN KEY FK_E90C9F5871F7E88B');
        $this->addSql('ALTER TABLE event_sponseur DROP FOREIGN KEY FK_E90C9F585008B3AF');
        $this->addSql('ALTER TABLE medicament DROP FOREIGN KEY FK_9A9C723A82EA2E54');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE etablissement');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_sponseur');
        $this->addSql('DROP TABLE medicament');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE sponseur');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
