<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200104063630 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE record_categories DROP CONSTRAINT FK_70B67299727ACA70');
        $this->addSql('ALTER TABLE record_categories ADD CONSTRAINT FK_70B67299727ACA70 FOREIGN KEY (parent_id) REFERENCES record_categories (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE record_categories DROP CONSTRAINT fk_70b67299727aca70');
        $this->addSql('ALTER TABLE record_categories ADD CONSTRAINT fk_70b67299727aca70 FOREIGN KEY (parent_id) REFERENCES record_categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
