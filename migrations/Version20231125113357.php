<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231125113357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE account_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE copy_definition_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE order_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE account (id INT NOT NULL, user_id INT DEFAULT NULL, login VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, trade_server VARCHAR(255) NOT NULL, mt_version INT NOT NULL, balance DOUBLE PRECISION NOT NULL, archived BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, archived_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A4AA08CB10 ON account (login)');
        $this->addSql('CREATE INDEX IDX_7D3656A4A76ED395 ON account (user_id)');
        $this->addSql('CREATE TABLE copy_definition (id INT NOT NULL, source_account_id INT DEFAULT NULL, target_account_id INT DEFAULT NULL, active BOOLEAN NOT NULL, archived BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, archived_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3D32BE0FE7DF2E9E ON copy_definition (source_account_id)');
        $this->addSql('CREATE INDEX IDX_3D32BE0FA987872B ON copy_definition (target_account_id)');
        $this->addSql('CREATE TABLE "order" (id INT NOT NULL, account_id INT DEFAULT NULL, copy_definition_id INT DEFAULT NULL, source_mt_order_id INT NOT NULL, position_id BIGINT NOT NULL, trade_symbol VARCHAR(255) NOT NULL, comment VARCHAR(255) NOT NULL, created_at_mt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expiration_mt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, done_mt TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, done TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, stop_loss DOUBLE PRECISION NOT NULL, take_profit DOUBLE PRECISION NOT NULL, profit DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F52993989B6B5FBA ON "order" (account_id)');
        $this->addSql('CREATE INDEX IDX_F5299398DF8C5086 ON "order" (copy_definition_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A4A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE copy_definition ADD CONSTRAINT FK_3D32BE0FE7DF2E9E FOREIGN KEY (source_account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE copy_definition ADD CONSTRAINT FK_3D32BE0FA987872B FOREIGN KEY (target_account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993989B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398DF8C5086 FOREIGN KEY (copy_definition_id) REFERENCES copy_definition (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE account_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE copy_definition_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE order_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('ALTER TABLE account DROP CONSTRAINT FK_7D3656A4A76ED395');
        $this->addSql('ALTER TABLE copy_definition DROP CONSTRAINT FK_3D32BE0FE7DF2E9E');
        $this->addSql('ALTER TABLE copy_definition DROP CONSTRAINT FK_3D32BE0FA987872B');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993989B6B5FBA');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398DF8C5086');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE copy_definition');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE "user"');
    }
}
