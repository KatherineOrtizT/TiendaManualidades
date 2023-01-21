<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230118175822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comentarios (id INT AUTO_INCREMENT NOT NULL, id_compra_id INT NOT NULL, descripcion LONGTEXT DEFAULT NULL, valoracion SMALLINT NOT NULL, UNIQUE INDEX UNIQ_F54B3FC072D2B8F0 (id_compra_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compras (id INT AUTO_INCREMENT NOT NULL, id_producto_id INT NOT NULL, id_pedido_id INT NOT NULL, INDEX IDX_3692E1B76E57A479 (id_producto_id), INDEX IDX_3692E1B7C861D91D (id_pedido_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pedidos (id INT AUTO_INCREMENT NOT NULL, id_usuario_id INT NOT NULL, direccion VARCHAR(255) NOT NULL, fecha DATE NOT NULL, INDEX IDX_6716CCAA7EB2C349 (id_usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comentarios ADD CONSTRAINT FK_F54B3FC072D2B8F0 FOREIGN KEY (id_compra_id) REFERENCES compras (id)');
        $this->addSql('ALTER TABLE compras ADD CONSTRAINT FK_3692E1B76E57A479 FOREIGN KEY (id_producto_id) REFERENCES producto (id)');
        $this->addSql('ALTER TABLE compras ADD CONSTRAINT FK_3692E1B7C861D91D FOREIGN KEY (id_pedido_id) REFERENCES pedidos (id)');
        $this->addSql('ALTER TABLE pedidos ADD CONSTRAINT FK_6716CCAA7EB2C349 FOREIGN KEY (id_usuario_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentarios DROP FOREIGN KEY FK_F54B3FC072D2B8F0');
        $this->addSql('ALTER TABLE compras DROP FOREIGN KEY FK_3692E1B76E57A479');
        $this->addSql('ALTER TABLE compras DROP FOREIGN KEY FK_3692E1B7C861D91D');
        $this->addSql('ALTER TABLE pedidos DROP FOREIGN KEY FK_6716CCAA7EB2C349');
        $this->addSql('DROP TABLE comentarios');
        $this->addSql('DROP TABLE compras');
        $this->addSql('DROP TABLE pedidos');
        $this->addSql('DROP TABLE user');
    }
}
