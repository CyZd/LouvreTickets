<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181111145246 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, tarif VARCHAR(255) DEFAULT NULL, pricing DOUBLE PRECISION DEFAULT NULL, low_value INT DEFAULT NULL, high_value INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, command_id INT NOT NULL, date DATETIME NOT NULL, price_tag SMALLINT NOT NULL, visitor_name VARCHAR(255) NOT NULL, visitor_sur_name VARCHAR(255) NOT NULL, visitor_country VARCHAR(255) NOT NULL, visitor_do_b DATETIME NOT NULL, reduced_price TINYINT(1) NOT NULL, INDEX IDX_97A0ADA333E1689A (command_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA333E1689A FOREIGN KEY (command_id) REFERENCES command (id)');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE tickets');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, tarif VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, pricing DOUBLE PRECISION DEFAULT NULL, low_value INT DEFAULT NULL, high_value INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tickets (id INT AUTO_INCREMENT NOT NULL, command_id INT NOT NULL, date DATETIME NOT NULL, price_tag SMALLINT NOT NULL, visitor_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, visitor_sur_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, visitor_country VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, visitor_do_b DATETIME NOT NULL, reduced_price TINYINT(1) NOT NULL, INDEX IDX_54469DF433E1689A (command_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF433E1689A FOREIGN KEY (command_id) REFERENCES command (id)');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE ticket');
    }
}
