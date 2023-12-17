<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231217142204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_file DROP FOREIGN KEY FK_2A03AB51A76ED395');
        $this->addSql('DROP INDEX IDX_2A03AB51A76ED395 ON chat_file');
        $this->addSql('ALTER TABLE chat_file DROP user_id');
        $this->addSql('ALTER TABLE chat_member CHANGE chat_id chat_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chat_member ADD CONSTRAINT FK_1738CD591A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE chat_member ADD CONSTRAINT FK_1738CD59A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_1738CD591A9A7125 ON chat_member (chat_id)');
        $this->addSql('CREATE INDEX IDX_1738CD59A76ED395 ON chat_member (user_id)');
        $this->addSql('ALTER TABLE chat_message CHANGE user_id user_id INT DEFAULT NULL, CHANGE chat_id chat_id INT DEFAULT NULL, CHANGE data date VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC161A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC16A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FAB3FC161A9A7125 ON chat_message (chat_id)');
        $this->addSql('CREATE INDEX IDX_FAB3FC16A76ED395 ON chat_message (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_file ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chat_file ADD CONSTRAINT FK_2A03AB51A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2A03AB51A76ED395 ON chat_file (user_id)');
        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC161A9A7125');
        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC16A76ED395');
        $this->addSql('DROP INDEX IDX_FAB3FC161A9A7125 ON chat_message');
        $this->addSql('DROP INDEX IDX_FAB3FC16A76ED395 ON chat_message');
        $this->addSql('ALTER TABLE chat_message CHANGE chat_id chat_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL, CHANGE date data VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chat_member DROP FOREIGN KEY FK_1738CD591A9A7125');
        $this->addSql('ALTER TABLE chat_member DROP FOREIGN KEY FK_1738CD59A76ED395');
        $this->addSql('DROP INDEX IDX_1738CD591A9A7125 ON chat_member');
        $this->addSql('DROP INDEX IDX_1738CD59A76ED395 ON chat_member');
        $this->addSql('ALTER TABLE chat_member CHANGE chat_id chat_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL');
    }
}
