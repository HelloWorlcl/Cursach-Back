<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181113194535 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE client DROP COLUMN IF EXISTS password');
        $this->addSql('ALTER TABLE client RENAME TO clients');
        $this->addSql('ALTER TABLE car RENAME TO cars');

    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE clients ADD COLUMN IF NOT EXISTS password VARCHAR');
        $this->addSql('ALTER TABLE clients RENAME TO client');
        $this->addSql('ALTER TABLE cars RENAME TO car');
    }
}
