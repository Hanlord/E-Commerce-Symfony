<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220808125442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipment ADD fk_address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DC5D965E6 FOREIGN KEY (fk_address_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_2CB20DC5D965E6 ON shipment (fk_address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DC5D965E6');
        $this->addSql('DROP INDEX IDX_2CB20DC5D965E6 ON shipment');
        $this->addSql('ALTER TABLE shipment DROP fk_address_id');
    }
}
