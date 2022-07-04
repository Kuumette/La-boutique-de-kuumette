<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220614092606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE order_line (id INT AUTO_INCREMENT NOT NULL, orders_id INT DEFAULT NULL, article VARCHAR(255) NOT NULL, quantity INT NOT NULL, price_ht DOUBLE PRECISION NOT NULL, size INT NOT NULL, INDEX IDX_9CE58EE1CFFE9AD6 (orders_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_line ADD CONSTRAINT FK_9CE58EE1CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE `order` DROP article, DROP quantity, DROP price_ht, DROP size');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE order_line');
        $this->addSql('ALTER TABLE `order` ADD article VARCHAR(50) NOT NULL, ADD quantity INT NOT NULL, ADD price_ht DOUBLE PRECISION NOT NULL, ADD size INT NOT NULL');
    }
}
