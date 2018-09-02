<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180902132843 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF410E71712');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4BF396750');
        $this->addSql('DROP INDEX IDX_54469DF410E71712 ON tickets');
        $this->addSql('ALTER TABLE tickets DROP current_order_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tickets ADD current_order_id INT NOT NULL');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF410E71712 FOREIGN KEY (current_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4BF396750 FOREIGN KEY (id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_54469DF410E71712 ON tickets (current_order_id)');
    }
}
