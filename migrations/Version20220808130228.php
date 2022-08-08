<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220808130228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE discount (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, discount_type VARCHAR(255) NOT NULL, amount INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD fk_category_id INT DEFAULT NULL, ADD fk_discount_id INT DEFAULT NULL, ADD availability INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD7BB031D6 FOREIGN KEY (fk_category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD258ACD2B FOREIGN KEY (fk_discount_id) REFERENCES discount (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD7BB031D6 ON product (fk_category_id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD258ACD2B ON product (fk_discount_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD258ACD2B');
        $this->addSql('DROP TABLE discount');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD7BB031D6');
        $this->addSql('DROP INDEX IDX_D34A04AD7BB031D6 ON product');
        $this->addSql('DROP INDEX IDX_D34A04AD258ACD2B ON product');
        $this->addSql('ALTER TABLE product DROP fk_category_id, DROP fk_discount_id, DROP availability');
    }
}
