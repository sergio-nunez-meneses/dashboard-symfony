<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200923071346 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, reference VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, purchase_date DATETIME NOT NULL, warranty_date DATETIME NOT NULL, price INT NOT NULL, receipt VARCHAR(255) NOT NULL, maintenance LONGTEXT NOT NULL, manual VARCHAR(255) NOT NULL, reservation_date DATETIME NOT NULL, return_date DATETIME NOT NULL, availability TINYINT(1) NOT NULL, purchase_place VARCHAR(255) NOT NULL, INDEX IDX_B3BA5A5A79F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A79F37AE5 FOREIGN KEY (id_user_id) REFERENCES users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A79F37AE5');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE users');
    }
}
