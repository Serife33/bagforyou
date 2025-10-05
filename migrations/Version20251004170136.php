<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251004170136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }
public function up(Schema $schema): void
{
    // 1) Nettoyer les valeurs NULL avant de changer les contraintes sur bag
    $this->addSql('UPDATE bag SET user_id = 1 WHERE user_id IS NULL');
    $this->addSql('UPDATE bag SET type_id = 1 WHERE type_id IS NULL');

    // 2) Ajouter la colonne username avant de l’utiliser
    $this->addSql('ALTER TABLE user ADD username VARCHAR(255) DEFAULT "temp_user"');

    // 3) Remplir la colonne nouvellement créée
    $this->addSql("UPDATE user SET username = CONCAT('user_', id) WHERE username IS NULL OR username = ''");

    // 4) Rendre les contraintes strictes
    $this->addSql('ALTER TABLE bag CHANGE user_id user_id INT NOT NULL, CHANGE type_id type_id INT NOT NULL');
    $this->addSql('ALTER TABLE user MODIFY username VARCHAR(255) NOT NULL');
}


    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bag CHANGE user_id user_id INT DEFAULT NULL, CHANGE type_id type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` DROP username');
    }
}
