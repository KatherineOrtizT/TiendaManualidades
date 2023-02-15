<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209113522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pregunta (id INT AUTO_INCREMENT NOT NULL, producto_id INT NOT NULL, user_id INT NOT NULL, texto LONGTEXT NOT NULL, fecha DATE NOT NULL, INDEX IDX_AEE0E1F77645698E (producto_id), INDEX IDX_AEE0E1F7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE respuesta (id INT AUTO_INCREMENT NOT NULL, pregunta_id INT NOT NULL, user_id INT NOT NULL, texto LONGTEXT NOT NULL, fecha DATE NOT NULL, INDEX IDX_6C6EC5EE31A5801E (pregunta_id), INDEX IDX_6C6EC5EEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pregunta ADD CONSTRAINT FK_AEE0E1F77645698E FOREIGN KEY (producto_id) REFERENCES producto (id)');
        $this->addSql('ALTER TABLE pregunta ADD CONSTRAINT FK_AEE0E1F7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE respuesta ADD CONSTRAINT FK_6C6EC5EE31A5801E FOREIGN KEY (pregunta_id) REFERENCES pregunta (id)');
        $this->addSql('ALTER TABLE respuesta ADD CONSTRAINT FK_6C6EC5EEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pregunta DROP FOREIGN KEY FK_AEE0E1F77645698E');
        $this->addSql('ALTER TABLE pregunta DROP FOREIGN KEY FK_AEE0E1F7A76ED395');
        $this->addSql('ALTER TABLE respuesta DROP FOREIGN KEY FK_6C6EC5EE31A5801E');
        $this->addSql('ALTER TABLE respuesta DROP FOREIGN KEY FK_6C6EC5EEA76ED395');
        $this->addSql('DROP TABLE pregunta');
        $this->addSql('DROP TABLE respuesta');
    }
}
