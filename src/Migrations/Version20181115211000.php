<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181115211000 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cars ALTER release_year TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE cars ALTER release_year DROP DEFAULT');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE cars ALTER release_year TYPE DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE cars ALTER release_year DROP DEFAULT');
    }
}
