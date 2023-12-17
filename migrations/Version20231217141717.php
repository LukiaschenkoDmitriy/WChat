<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231217141717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_file CHANGE chat_id chat_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chat_file ADD CONSTRAINT FK_2A03AB511A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE chat_file ADD CONSTRAINT FK_2A03AB51A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2A03AB511A9A7125 ON chat_file (chat_id)');
        $this->addSql('CREATE INDEX IDX_2A03AB51A76ED395 ON chat_file (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_file DROP FOREIGN KEY FK_2A03AB511A9A7125');
        $this->addSql('ALTER TABLE chat_file DROP FOREIGN KEY FK_2A03AB51A76ED395');
        $this->addSql('DROP INDEX IDX_2A03AB511A9A7125 ON chat_file');
        $this->addSql('DROP INDEX IDX_2A03AB51A76ED395 ON chat_file');
        $this->addSql('ALTER TABLE chat_file CHANGE chat_id chat_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL');
    }
}
