<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231126114741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account ALTER balance DROP NOT NULL');
        $this->addSql('ALTER TABLE "order" ALTER source_mt_order_id DROP NOT NULL');
        $this->addSql('ALTER TABLE "order" ALTER done_mt DROP NOT NULL');
        $this->addSql('ALTER TABLE "order" ALTER done DROP NOT NULL');
        $this->addSql('ALTER TABLE "order" ALTER profit DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE account ALTER balance SET NOT NULL');
        $this->addSql('ALTER TABLE "order" ALTER source_mt_order_id SET NOT NULL');
        $this->addSql('ALTER TABLE "order" ALTER done_mt SET NOT NULL');
        $this->addSql('ALTER TABLE "order" ALTER done SET NOT NULL');
        $this->addSql('ALTER TABLE "order" ALTER profit SET NOT NULL');
    }
}
