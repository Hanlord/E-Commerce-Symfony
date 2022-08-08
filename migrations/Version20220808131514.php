<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220808131514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, fk_product_id INT DEFAULT NULL, fk_user_id INT DEFAULT NULL, fk_order_id INT DEFAULT NULL, datetime DATETIME DEFAULT NULL, INDEX IDX_BA388B7B5EAACC9 (fk_product_id), INDEX IDX_BA388B75741EEB9 (fk_user_id), INDEX IDX_BA388B7BFB72EE6 (fk_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7B5EAACC9 FOREIGN KEY (fk_product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B75741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7BFB72EE6 FOREIGN KEY (fk_order_id) REFERENCES `order` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7B5EAACC9');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B75741EEB9');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7BFB72EE6');
        $this->addSql('DROP TABLE cart');
    }
}
