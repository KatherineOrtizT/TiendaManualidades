<?php

namespace App\Entity;

use App\Repository\DatosDePagoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DatosDePagoRepository::class)]
class DatosDePago
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $numeroTarjeta = null;

    #[ORM\Column(length: 50)]
    private ?string $titularNombre = null;

    #[ORM\Column(length: 4)]
    private ?string $codigoDeSeguridad = null;

    #[ORM\Column(length: 100)]
    private ?string $direccionFacturacion = null;

    #[ORM\OneToOne(inversedBy: 'datosDePago', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroTarjeta(): ?string
    {
        return $this->numeroTarjeta;
    }

    public function setNumeroTarjeta(string $numeroTarjeta): self
    {
        $this->numeroTarjeta = $numeroTarjeta;

        return $this;
    }

    public function getTitularNombre(): ?string
    {
        return $this->titularNombre;
    }

    public function setTitularNombre(string $titularNombre): self
    {
        $this->titularNombre = $titularNombre;

        return $this;
    }

    public function getCodigoDeSeguridad(): ?string
    {
        return $this->codigoDeSeguridad;
    }

    public function setCodigoDeSeguridad(string $codigoDeSeguridad): self
    {
        $this->codigoDeSeguridad = $codigoDeSeguridad;

        return $this;
    }

    public function getDireccionFacturacion(): ?string
    {
        return $this->direccionFacturacion;
    }

    public function setDireccionFacturacion(string $direccionFacturacion): self
    {
        $this->direccionFacturacion = $direccionFacturacion;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(user $user): self
    {
        $this->user = $user;

        return $this;
    }
}
