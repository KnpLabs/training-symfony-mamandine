<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220321100832 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `orders` (id INT AUTO_INCREMENT NOT NULL, cake_id INT NOT NULL, buyer_id INT NOT NULL, validation_status VARCHAR(255) NOT NULL, creation_date DATETIME NOT NULL, number INT NOT NULL, INDEX IDX_F52993989F8008B6 (cake_id), INDEX IDX_F52993986C755722 (buyer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `orders` ADD CONSTRAINT FK_F52993989F8008B6 FOREIGN KEY (cake_id) REFERENCES cake (id)');
        $this->addSql('ALTER TABLE `orders` ADD CONSTRAINT FK_F52993986C755722 FOREIGN KEY (buyer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cake ADD buyable TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `orders`');
        $this->addSql('ALTER TABLE cake DROP buyable');
    }
}
