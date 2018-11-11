<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181111210125 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TYPE fuel_types AS ENUM (\'petrol\', \'diesel\', \'gas\', \'electric\', \'hybrid\', \'other\')');
        $this->addSql('CREATE TYPE transmission_types AS ENUM (\'manual\', \'automatic\', \'automated_manual\', \'continuously_variable\', \'other\')');
        $this->addSql('CREATE TYPE drive_unit_types AS ENUM (\'front-wheel\', \'rear\', \'four-wheel\', \'other\')');

        $this->addSql('CREATE SEQUENCE car_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE car (id INT NOT NULL, owner_id INT DEFAULT NULL, mark VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, release_year DATE DEFAULT NULL, mileage INT DEFAULT NULL, engine_capacity INT DEFAULT NULL, fuel fuel_types, transmission transmission_types, drive_unit drive_unit_types, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_773DE69D7E3C61F9 ON car (owner_id)');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455E7927C74 ON client (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7440455444F97DD ON client (phone)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TYPE fuel_types');
        $this->addSql('DROP TYPE transmission_types');
        $this->addSql('DROP TYPE drive_unit_types');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69D7E3C61F9');
        $this->addSql('DROP SEQUENCE car_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE client_id_seq CASCADE');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE client');
    }
}
