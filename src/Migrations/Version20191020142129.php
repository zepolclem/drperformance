<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191020142129 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE engine (id INT AUTO_INCREMENT NOT NULL, generation_id INT NOT NULL, power INT DEFAULT NULL, torque INT DEFAULT NULL, energy ENUM(\'GAS\', \'DIE\', \'ELE\') DEFAULT NULL COMMENT \'(DC2Type:EngineEnergyType)\', cylinder_capacity INT DEFAULT NULL, turbo TINYINT(1) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_E8A81A8D553A6EC4 (generation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE map (id INT AUTO_INCREMENT NOT NULL, engine_id INT NOT NULL, power INT DEFAULT NULL, torque INT DEFAULT NULL, resume LONGTEXT DEFAULT NULL, price NUMERIC(10, 2) DEFAULT NULL, name VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, slug VARCHAR(255) DEFAULT NULL, INDEX IDX_93ADAABBE78C9C0A (engine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE generation (id INT AUTO_INCREMENT NOT NULL, model_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, resume LONGTEXT DEFAULT NULL, slug VARCHAR(255) NOT NULL, start_year INT NOT NULL, end_year INT DEFAULT NULL, picture LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_D3266C3B7975B7E7 (model_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, manufacturer_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, resume LONGTEXT DEFAULT NULL, slug VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_D79572D9A23B42D (manufacturer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manufacturer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, resume LONGTEXT DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE engine ADD CONSTRAINT FK_E8A81A8D553A6EC4 FOREIGN KEY (generation_id) REFERENCES generation (id)');
        $this->addSql('ALTER TABLE map ADD CONSTRAINT FK_93ADAABBE78C9C0A FOREIGN KEY (engine_id) REFERENCES engine (id)');
        $this->addSql('ALTER TABLE generation ADD CONSTRAINT FK_D3266C3B7975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D9A23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE map DROP FOREIGN KEY FK_93ADAABBE78C9C0A');
        $this->addSql('ALTER TABLE engine DROP FOREIGN KEY FK_E8A81A8D553A6EC4');
        $this->addSql('ALTER TABLE generation DROP FOREIGN KEY FK_D3266C3B7975B7E7');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D9A23B42D');
        $this->addSql('DROP TABLE engine');
        $this->addSql('DROP TABLE map');
        $this->addSql('DROP TABLE generation');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE manufacturer');
    }
}
