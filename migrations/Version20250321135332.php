<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250321135332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id BIGINT AUTO_INCREMENT NOT NULL, city VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin (id BIGINT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agent (id BIGINT AUTO_INCREMENT NOT NULL, full_name VARCHAR(255) NOT NULL, email VARCHAR(150) NOT NULL, phone_number INT NOT NULL, creation_date DATE DEFAULT NULL, active_status TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_268B9C9DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE citizen (id BIGINT AUTO_INCREMENT NOT NULL, address_id BIGINT DEFAULT NULL, full_name VARCHAR(255) NOT NULL, email VARCHAR(150) NOT NULL, cin INT NOT NULL, phone_number VARCHAR(20) NOT NULL, score INT DEFAULT NULL, level INT DEFAULT NULL, creation_date DATETIME NOT NULL, last_modified_date DATETIME NOT NULL, active_status TINYINT(1) DEFAULT NULL, image_path LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_A9531729E7927C74 (email), INDEX IDX_A9531729F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id BIGINT AUTO_INCREMENT NOT NULL, post_id BIGINT DEFAULT NULL, citizen_id BIGINT DEFAULT NULL, content VARCHAR(255) NOT NULL, creation_date DATE NOT NULL, last_modified_date DATE DEFAULT NULL, INDEX IDX_9474526C4B89032C (post_id), INDEX IDX_9474526CA63C3C2E (citizen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_citizen (id BIGINT AUTO_INCREMENT NOT NULL, event_id BIGINT DEFAULT NULL, citizen_id BIGINT DEFAULT NULL, INDEX IDX_2C04BE8671F7E88B (event_id), INDEX IDX_2C04BE86A63C3C2E (citizen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE events (id BIGINT AUTO_INCREMENT NOT NULL, location_id BIGINT DEFAULT NULL, citizen_id BIGINT DEFAULT NULL, title VARCHAR(30) NOT NULL, description VARCHAR(255) DEFAULT NULL, date DATE NOT NULL, creation_date DATETIME NOT NULL, last_modified_date DATETIME NOT NULL, image_path VARCHAR(200) NOT NULL, INDEX IDX_5387574A64D218E (location_id), INDEX IDX_5387574AA63C3C2E (citizen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE new_entity (id INT AUTO_INCREMENT NOT NULL, first_thin VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifications (id BIGINT AUTO_INCREMENT NOT NULL, report_id BIGINT DEFAULT NULL, event_id BIGINT DEFAULT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, seen TINYINT(1) DEFAULT NULL, INDEX IDX_6000B0D34BD2A4C0 (report_id), INDEX IDX_6000B0D371F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id BIGINT AUTO_INCREMENT NOT NULL, citizen_id BIGINT DEFAULT NULL, title VARCHAR(50) NOT NULL, content LONGTEXT DEFAULT NULL, file_url VARCHAR(255) DEFAULT NULL, tags VARCHAR(255) DEFAULT NULL, creation_date DATETIME NOT NULL, last_modified_date DATETIME NOT NULL, INDEX IDX_5A8A6C8DA63C3C2E (citizen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quest (id BIGINT AUTO_INCREMENT NOT NULL, citizen_id BIGINT DEFAULT NULL, location_id BIGINT DEFAULT NULL, title VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, status VARCHAR(255) NOT NULL, date DATE NOT NULL, creation_date DATETIME NOT NULL, last_modified_date DATETIME NOT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_4317F817A63C3C2E (citizen_id), INDEX IDX_4317F81764D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE react (id BIGINT AUTO_INCREMENT NOT NULL, post_id BIGINT DEFAULT NULL, citizen_id BIGINT DEFAULT NULL, is_liked TINYINT(1) DEFAULT NULL, INDEX IDX_19656FD54B89032C (post_id), INDEX IDX_19656FD5A63C3C2E (citizen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id BIGINT AUTO_INCREMENT NOT NULL, citizen_id BIGINT DEFAULT NULL, title VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, creation_date DATETIME NOT NULL, last_modified_date DATETIME NOT NULL, report_type VARCHAR(50) DEFAULT NULL, attachments LONGTEXT DEFAULT NULL, response_status VARCHAR(255) NOT NULL, response_text LONGTEXT DEFAULT NULL, response_date DATETIME NOT NULL, INDEX IDX_C42F7784A63C3C2E (citizen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id BIGINT AUTO_INCREMENT NOT NULL, citizen_id BIGINT DEFAULT NULL, admin_id BIGINT DEFAULT NULL, agent_id BIGINT DEFAULT NULL, username VARCHAR(100) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D649A63C3C2E (citizen_id), INDEX IDX_8D93D649642B8210 (admin_id), INDEX IDX_8D93D6493414710B (agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wastecollection (id BIGINT AUTO_INCREMENT NOT NULL, location_id BIGINT DEFAULT NULL, date_time DATETIME NOT NULL, INDEX IDX_456A25F164D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE citizen ADD CONSTRAINT FK_A9531729F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA63C3C2E FOREIGN KEY (citizen_id) REFERENCES citizen (id)');
        $this->addSql('ALTER TABLE event_citizen ADD CONSTRAINT FK_2C04BE8671F7E88B FOREIGN KEY (event_id) REFERENCES events (id)');
        $this->addSql('ALTER TABLE event_citizen ADD CONSTRAINT FK_2C04BE86A63C3C2E FOREIGN KEY (citizen_id) REFERENCES citizen (id)');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A64D218E FOREIGN KEY (location_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574AA63C3C2E FOREIGN KEY (citizen_id) REFERENCES citizen (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D34BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D371F7E88B FOREIGN KEY (event_id) REFERENCES events (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA63C3C2E FOREIGN KEY (citizen_id) REFERENCES citizen (id)');
        $this->addSql('ALTER TABLE quest ADD CONSTRAINT FK_4317F817A63C3C2E FOREIGN KEY (citizen_id) REFERENCES citizen (id)');
        $this->addSql('ALTER TABLE quest ADD CONSTRAINT FK_4317F81764D218E FOREIGN KEY (location_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE react ADD CONSTRAINT FK_19656FD54B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE react ADD CONSTRAINT FK_19656FD5A63C3C2E FOREIGN KEY (citizen_id) REFERENCES citizen (id)');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784A63C3C2E FOREIGN KEY (citizen_id) REFERENCES citizen (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649A63C3C2E FOREIGN KEY (citizen_id) REFERENCES citizen (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649642B8210 FOREIGN KEY (admin_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('ALTER TABLE wastecollection ADD CONSTRAINT FK_456A25F164D218E FOREIGN KEY (location_id) REFERENCES address (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE citizen DROP FOREIGN KEY FK_A9531729F5B7AF75');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA63C3C2E');
        $this->addSql('ALTER TABLE event_citizen DROP FOREIGN KEY FK_2C04BE8671F7E88B');
        $this->addSql('ALTER TABLE event_citizen DROP FOREIGN KEY FK_2C04BE86A63C3C2E');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A64D218E');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574AA63C3C2E');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D34BD2A4C0');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D371F7E88B');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA63C3C2E');
        $this->addSql('ALTER TABLE quest DROP FOREIGN KEY FK_4317F817A63C3C2E');
        $this->addSql('ALTER TABLE quest DROP FOREIGN KEY FK_4317F81764D218E');
        $this->addSql('ALTER TABLE react DROP FOREIGN KEY FK_19656FD54B89032C');
        $this->addSql('ALTER TABLE react DROP FOREIGN KEY FK_19656FD5A63C3C2E');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784A63C3C2E');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649A63C3C2E');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649642B8210');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493414710B');
        $this->addSql('ALTER TABLE wastecollection DROP FOREIGN KEY FK_456A25F164D218E');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE agent');
        $this->addSql('DROP TABLE citizen');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE event_citizen');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE new_entity');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE quest');
        $this->addSql('DROP TABLE react');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wastecollection');
    }
}
