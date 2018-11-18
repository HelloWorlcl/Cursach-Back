<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181118131328 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE car_breakdown_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE car_breakdown (id INT NOT NULL, car_id INT NOT NULL, breakdown_id INT NOT NULL, description VARCHAR(255) DEFAULT NULL, min_price INT DEFAULT NULL, max_price INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B4746F8BC3C6F69F ON car_breakdown (car_id)');
        $this->addSql('CREATE INDEX IDX_B4746F8B67F54C40 ON car_breakdown (breakdown_id)');
        $this->addSql('ALTER TABLE car_breakdown ADD CONSTRAINT FK_B4746F8BC3C6F69F FOREIGN KEY (car_id) REFERENCES cars (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car_breakdown ADD CONSTRAINT FK_B4746F8B67F54C40 FOREIGN KEY (breakdown_id) REFERENCES breakdowns (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE car_breakdown_id_seq CASCADE');
        $this->addSql('DROP TABLE car_breakdown');
    }
}
