<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508164207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE basket_items (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', product_uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', basket_uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', amount INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:carbon_date_time)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:carbon_date_time)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:carbon_date_time)\', INDEX IDX_B766A2775C977207 (product_uuid), INDEX IDX_B766A2771AF04993 (basket_uuid), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE baskets (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:carbon_date_time)\', checked_out_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:carbon_date_time)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:carbon_date_time)\', PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, price INT NOT NULL, UNIQUE INDEX UNIQ_B3BA5A5A5E237E06 (name), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `refresh_tokens` (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (uuid VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(uuid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE basket_items ADD CONSTRAINT FK_B766A2775C977207 FOREIGN KEY (product_uuid) REFERENCES products (uuid)');
        $this->addSql('ALTER TABLE basket_items ADD CONSTRAINT FK_B766A2771AF04993 FOREIGN KEY (basket_uuid) REFERENCES baskets (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE basket_items DROP FOREIGN KEY FK_B766A2775C977207');
        $this->addSql('ALTER TABLE basket_items DROP FOREIGN KEY FK_B766A2771AF04993');
        $this->addSql('DROP TABLE basket_items');
        $this->addSql('DROP TABLE baskets');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE `refresh_tokens`');
        $this->addSql('DROP TABLE users');
    }
}
