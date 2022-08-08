<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220808131111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review ADD fk_user_id INT DEFAULT NULL, ADD fk_product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C65741EEB9 FOREIGN KEY (fk_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6B5EAACC9 FOREIGN KEY (fk_product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_794381C65741EEB9 ON review (fk_user_id)');
        $this->addSql('CREATE INDEX IDX_794381C6B5EAACC9 ON review (fk_product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C65741EEB9');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6B5EAACC9');
        $this->addSql('DROP INDEX IDX_794381C65741EEB9 ON review');
        $this->addSql('DROP INDEX IDX_794381C6B5EAACC9 ON review');
        $this->addSql('ALTER TABLE review DROP fk_user_id, DROP fk_product_id');
    }
}
