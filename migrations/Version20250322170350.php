<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250322170350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, number_tel INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id BIGINT AUTO_INCREMENT NOT NULL, sender_id BIGINT DEFAULT NULL, receiver_id BIGINT DEFAULT NULL, content LONGTEXT NOT NULL, timestamp DATETIME NOT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FCD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES citizen (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES citizen (id)');
        $this->addSql('DROP TABLE new_entity');
        $this->addSql('ALTER TABLE user DROP INDEX IDX_8D93D649A63C3C2E, ADD UNIQUE INDEX UNIQ_8D93D649A63C3C2E (citizen_id)');
        $this->addSql('ALTER TABLE user DROP INDEX IDX_8D93D649642B8210, ADD UNIQUE INDEX UNIQ_8D93D649642B8210 (admin_id)');
        $this->addSql('ALTER TABLE user DROP INDEX IDX_8D93D6493414710B, ADD UNIQUE INDEX UNIQ_8D93D6493414710B (agent_id)');
        $this->addSql('ALTER TABLE user ADD reset_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE new_entity (id INT AUTO_INCREMENT NOT NULL, first_thin VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE message');
        $this->addSql('ALTER TABLE user DROP INDEX UNIQ_8D93D649A63C3C2E, ADD INDEX IDX_8D93D649A63C3C2E (citizen_id)');
        $this->addSql('ALTER TABLE user DROP INDEX UNIQ_8D93D649642B8210, ADD INDEX IDX_8D93D649642B8210 (admin_id)');
        $this->addSql('ALTER TABLE user DROP INDEX UNIQ_8D93D6493414710B, ADD INDEX IDX_8D93D6493414710B (agent_id)');
        $this->addSql('ALTER TABLE user DROP reset_token');
    }
}
