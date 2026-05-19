<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260519124743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gallery_entity (id VARCHAR(44) NOT NULL, owner_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email_address VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_ACABA8CA7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image_entity (id INT AUTO_INCREMENT NOT NULL, gallery_id VARCHAR(44) NOT NULL, name VARCHAR(255) NOT NULL, uploaded_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A1351AA04E7AF8F (gallery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_entity (id INT AUTO_INCREMENT NOT NULL, user_id VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, gallery_id VARCHAR(255) NOT NULL, is_read TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recover_token_entity (id INT AUTO_INCREMENT NOT NULL, user_ref_id VARCHAR(255) NOT NULL, token_hash VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', used_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_4BC5496AB3BC57DA (token_hash), INDEX IDX_4BC5496A44E55A94 (user_ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_token (id VARCHAR(255) NOT NULL, user_ref_id VARCHAR(255) NOT NULL, token VARCHAR(128) NOT NULL, expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', revoked_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C74F219544E55A94 (user_ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, activation_token VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gallery_entity ADD CONSTRAINT FK_ACABA8CA7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE image_entity ADD CONSTRAINT FK_A1351AA04E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery_entity (id)');
        $this->addSql('ALTER TABLE recover_token_entity ADD CONSTRAINT FK_4BC5496A44E55A94 FOREIGN KEY (user_ref_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE refresh_token ADD CONSTRAINT FK_C74F219544E55A94 FOREIGN KEY (user_ref_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gallery_entity DROP FOREIGN KEY FK_ACABA8CA7E3C61F9');
        $this->addSql('ALTER TABLE image_entity DROP FOREIGN KEY FK_A1351AA04E7AF8F');
        $this->addSql('ALTER TABLE recover_token_entity DROP FOREIGN KEY FK_4BC5496A44E55A94');
        $this->addSql('ALTER TABLE refresh_token DROP FOREIGN KEY FK_C74F219544E55A94');
        $this->addSql('DROP TABLE gallery_entity');
        $this->addSql('DROP TABLE image_entity');
        $this->addSql('DROP TABLE notification_entity');
        $this->addSql('DROP TABLE recover_token_entity');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE `user`');
    }
}
