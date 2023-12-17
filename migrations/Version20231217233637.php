<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231217233637 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_notification (id INT AUTO_INCREMENT NOT NULL, chat_id INT DEFAULT NULL, user_id INT DEFAULT NULL, notifications INT DEFAULT NULL, INDEX IDX_3F980AC81A9A7125 (chat_id), INDEX IDX_3F980AC8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_notification ADD CONSTRAINT FK_3F980AC81A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE user_notification ADD CONSTRAINT FK_3F980AC8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_notification DROP FOREIGN KEY FK_3F980AC81A9A7125');
        $this->addSql('ALTER TABLE user_notification DROP FOREIGN KEY FK_3F980AC8A76ED395');
        $this->addSql('DROP TABLE user_notification');
    }
}
