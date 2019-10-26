<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191026143248 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE manufacturer DROP FOREIGN KEY FK_3D0AE6DCC54C8C93');
        $this->addSql('DROP TABLE type_vehicle');
        $this->addSql('DROP INDEX IDX_3D0AE6DCC54C8C93 ON manufacturer');
        $this->addSql('ALTER TABLE manufacturer ADD type ENUM(\'CAR\', \'BIKE\', \'ATV\', \'JET\') DEFAULT NULL COMMENT \'(DC2Type:VehicleType)\', DROP type_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE type_vehicle (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE manufacturer ADD type_id INT DEFAULT NULL, DROP type');
        $this->addSql('ALTER TABLE manufacturer ADD CONSTRAINT FK_3D0AE6DCC54C8C93 FOREIGN KEY (type_id) REFERENCES type_vehicle (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3D0AE6DCC54C8C93 ON manufacturer (type_id)');
    }
}
