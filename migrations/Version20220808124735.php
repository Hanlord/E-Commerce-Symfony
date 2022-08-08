<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220808124735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address ADD fk_address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F815D965E6 FOREIGN KEY (fk_address_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D4E6F815D965E6 ON address (fk_address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F815D965E6');
        $this->addSql('DROP INDEX IDX_D4E6F815D965E6 ON address');
        $this->addSql('ALTER TABLE address DROP fk_address_id');
    }
}
