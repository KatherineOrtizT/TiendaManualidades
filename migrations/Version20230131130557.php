<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230131130557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE datos_de_pago (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, numero_tarjeta VARCHAR(50) NOT NULL, titular_nombre VARCHAR(50) NOT NULL, codigo_de_seguridad VARCHAR(4) NOT NULL, direccion_facturacion VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_11EE98FCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE datos_de_pago ADD CONSTRAINT FK_11EE98FCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE datos_de_pago DROP FOREIGN KEY FK_11EE98FCA76ED395');
        $this->addSql('DROP TABLE datos_de_pago');
    }
}
