<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240316200840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jwtoken DROP email');
        $this->addSql('ALTER TABLE user ADD jwtoken_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64995716A03 FOREIGN KEY (jwtoken_id) REFERENCES jwtoken (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64995716A03 ON user (jwtoken_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64995716A03');
        $this->addSql('DROP INDEX UNIQ_8D93D64995716A03 ON `user`');
        $this->addSql('ALTER TABLE `user` DROP jwtoken_id');
        $this->addSql('ALTER TABLE jwtoken ADD email VARCHAR(255) DEFAULT NULL');
    }
}
