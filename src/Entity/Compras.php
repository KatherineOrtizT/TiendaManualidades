<?php

namespace App\Entity;

use App\Repository\ComprasRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ComprasRepository::class)]
class Compras
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'compras')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Producto $idProducto = null;

    #[ORM\ManyToOne(inversedBy: 'compras')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pedidos $idPedido = null;

    #[ORM\OneToOne(mappedBy: 'idCompra', cascade: ['persist', 'remove'])]
    private ?Comentarios $comentarios = null;

    #[ORM\Column]
    private ?int $unidades = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $precio_compra = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdProducto(): ?Producto
    {
        return $this->idProducto;
    }

    public function setIdProducto(?Producto $idProducto): self
    {
        $this->idProducto = $idProducto;

        return $this;
    }

    public function getIdPedido(): ?Pedidos
    {
        return $this->idPedido;
    }

    public function setIdPedido(?Pedidos $idPedido): self
    {
        $this->idPedido = $idPedido;

        return $this;
    }

    public function getComentarios(): ?Comentarios
    {
        return $this->comentarios;
    }

    public function setComentarios(Comentarios $comentarios): self
    {
        // set the owning side of the relation if necessary
        if ($comentarios->getIdCompra() !== $this) {
            $comentarios->setIdCompra($this);
        }

        $this->comentarios = $comentarios;

        return $this;
    }

    public function getUnidades(): ?int
    {
        return $this->unidades;
    }

    public function setUnidades(int $unidades): self
    {
        $this->unidades = $unidades;

        return $this;
    }

    public function getPrecioCompra(): ?string
    {
        return $this->precio_compra;
    }

    public function setPrecioCompra(string $precio_compra): self
    {
        $this->precio_compra = $precio_compra;

        return $this;
    }
}
