<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231217154231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat ADD last_message_id INT DEFAULT NULL, DROP last_message');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AABA0E79C3 FOREIGN KEY (last_message_id) REFERENCES chat_message (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AABA0E79C3 ON chat (last_message_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AABA0E79C3');
        $this->addSql('DROP INDEX IDX_659DF2AABA0E79C3 ON chat');
        $this->addSql('ALTER TABLE chat ADD last_message VARCHAR(255) DEFAULT NULL, DROP last_message_id');
    }
}
