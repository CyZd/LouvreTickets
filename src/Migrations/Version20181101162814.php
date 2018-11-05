<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181101162814 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE command ADD date DATETIME NOT NULL, ADD desired_date DATETIME NOT NULL, ADD day_type SMALLINT NOT NULL, ADD has_been_paid TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE tickets DROP desired_date, DROP day_type');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE command DROP date, DROP desired_date, DROP day_type, DROP has_been_paid');
        $this->addSql('ALTER TABLE tickets ADD desired_date DATETIME NOT NULL, ADD day_type SMALLINT NOT NULL');
    }
}
