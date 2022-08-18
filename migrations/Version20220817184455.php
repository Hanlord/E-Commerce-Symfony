<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220817184455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B75741EEB9');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7B5EAACC9');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7BFB72EE6');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B75741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7B5EAACC9 FOREIGN KEY (fk_product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7BFB72EE6 FOREIGN KEY (fk_order_id) REFERENCES `order` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7B5EAACC9');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B75741EEB9');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7BFB72EE6');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7B5EAACC9 FOREIGN KEY (fk_product_id) REFERENCES product (id) ON UPDATE SET NULL ON DELETE SET NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B75741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id) ON UPDATE SET NULL ON DELETE SET NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7BFB72EE6 FOREIGN KEY (fk_order_id) REFERENCES `order` (id) ON UPDATE SET NULL ON DELETE SET NULL');
    }
}
