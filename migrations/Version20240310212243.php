<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240310212243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE points (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, above_points LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', under_points LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', match_lost_points INT NOT NULL, UNIQUE INDEX UNIQ_27BA8E29A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE points ADD CONSTRAINT FK_27BA8E29A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE points DROP FOREIGN KEY FK_27BA8E29A76ED395');
        $this->addSql('DROP TABLE points');
    }
}
