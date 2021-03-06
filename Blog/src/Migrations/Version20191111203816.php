<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191111203816 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post ADD title VARCHAR(280) NOT NULL');
        $this->addSql('ALTER TABLE notification CHANGE user_id user_id INT DEFAULT NULL, CHANGE post_id post_id INT DEFAULT NULL, CHANGE liked_by_id liked_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE preferences_id preferences_id INT DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(30) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE notification CHANGE user_id user_id INT DEFAULT NULL, CHANGE post_id post_id INT DEFAULT NULL, CHANGE liked_by_id liked_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post DROP title');
        $this->addSql('ALTER TABLE user CHANGE preferences_id preferences_id INT DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(30) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
