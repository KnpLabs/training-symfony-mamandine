<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220301164220 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Cake + Category + User';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cake (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cake_category (cake_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_850446329F8008B6 (cake_id), INDEX IDX_8504463212469DE2 (category_id), PRIMARY KEY(cake_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cake_category ADD CONSTRAINT FK_850446329F8008B6 FOREIGN KEY (cake_id) REFERENCES cake (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cake_category ADD CONSTRAINT FK_8504463212469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cake_category DROP FOREIGN KEY FK_850446329F8008B6');
        $this->addSql('ALTER TABLE cake_category DROP FOREIGN KEY FK_8504463212469DE2');
        $this->addSql('DROP TABLE cake');
        $this->addSql('DROP TABLE cake_category');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE user');
    }
}
