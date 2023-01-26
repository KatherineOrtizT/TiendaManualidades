<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\OneToMany(mappedBy: 'idUsuario', targetEntity: Pedidos::class, orphanRemoval: true)]
    private Collection $pedidos;

    #[ORM\Column(length: 255)]
    private ?string $Nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $Apellidos = null;

    public function __construct($nombre=null,$apellidos=null,$id=null,$email=null,$password=null)
    {
        $this->Nombre = $nombre;
        $this->Apellidos = $apellidos;
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->pedidos = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Pedidos>
     */
    public function getPedidos(): Collection
    {
        return $this->pedidos;
    }

    public function addPedido(Pedidos $pedido): self
    {
        if (!$this->pedidos->contains($pedido)) {
            $this->pedidos->add($pedido);
            $pedido->setIdUsuario($this);
        }

        return $this;
    }

    public function removePedido(Pedidos $pedido): self
    {
        if ($this->pedidos->removeElement($pedido)) {
            // set the owning side to null (unless already changed)
            if ($pedido->getIdUsuario() === $this) {
                $pedido->setIdUsuario(null);
            }
        }

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->Nombre;
    }

    public function setNombre(string $Nombre): self
    {
        $this->Nombre = $Nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->Apellidos;
    }

    public function setApellidos(string $Apellidos): self
    {
        $this->Apellidos = $Apellidos;

        return $this;
    }

 
    
}
