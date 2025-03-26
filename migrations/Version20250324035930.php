<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250324035930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin ADD user_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_880E0D76A76ED395 ON admin (user_id)');
        $this->addSql('ALTER TABLE agent ADD user_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_268B9C9DA76ED395 ON agent (user_id)');
        $this->addSql('ALTER TABLE citizen ADD user_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE citizen ADD CONSTRAINT FK_A9531729A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A9531729A76ED395 ON citizen (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D76A76ED395');
        $this->addSql('DROP INDEX UNIQ_880E0D76A76ED395 ON admin');
        $this->addSql('ALTER TABLE admin DROP user_id');
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9DA76ED395');
        $this->addSql('DROP INDEX UNIQ_268B9C9DA76ED395 ON agent');
        $this->addSql('ALTER TABLE agent DROP user_id');
        $this->addSql('ALTER TABLE citizen DROP FOREIGN KEY FK_A9531729A76ED395');
        $this->addSql('DROP INDEX UNIQ_A9531729A76ED395 ON citizen');
        $this->addSql('ALTER TABLE citizen DROP user_id');
    }
}
