<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Validator\UniqueEmail;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email(message: 'El email {{ value }} no es un formato de email válido.')]
    /* #[UniqueEmail(message:"El email ya está en uso")] */
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Regex(pattern:"/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/", message:"La contraseña no es válida. Debe contener al menos 8 caracteres, un número, una letra mayúscula y una letra minúscula")]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Regex(pattern:"/^[\w]+\.(jpg|jpeg|png|webp)$/i", message:"Foto no válida")]
    private ?string $photo = null;

    #[ORM\OneToMany(mappedBy: 'idUsuario', targetEntity: Pedidos::class, orphanRemoval: true)]
    private Collection $pedidos;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"El campo Nombre no puede estar vacío")]
    private ?string $Nombre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"El campo Apellidos no puede estar vacío")]
    private ?string $Apellidos = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Pregunta::class, orphanRemoval: true)]
    private Collection $preguntas;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Respuesta::class, orphanRemoval: true)]
    private Collection $respuestas;

    public function __construct($nombre=null,$apellidos=null,$id=null,$email=null,$password=null)
    {
        $this->Nombre = $nombre;
        $this->Apellidos = $apellidos;
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->pedidos = new ArrayCollection();
        $this->preguntas = new ArrayCollection();
        $this->respuestas = new ArrayCollection();
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

    /**
     * @return Collection<int, Pregunta>
     */
    public function getPreguntas(): Collection
    {
        return $this->preguntas;
    }

    public function addPregunta(Pregunta $pregunta): self
    {
        if (!$this->preguntas->contains($pregunta)) {
            $this->preguntas->add($pregunta);
            $pregunta->setUser($this);
        }

        return $this;
    }

    public function removePregunta(Pregunta $pregunta): self
    {
        if ($this->preguntas->removeElement($pregunta)) {
            // set the owning side to null (unless already changed)
            if ($pregunta->getUser() === $this) {
                $pregunta->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Respuesta>
     */
    public function getRespuestas(): Collection
    {
        return $this->respuestas;
    }

    public function addRespuesta(Respuesta $respuesta): self
    {
        if (!$this->respuestas->contains($respuesta)) {
            $this->respuestas->add($respuesta);
            $respuesta->setUser($this);
        }

        return $this;
    }

    public function removeRespuesta(Respuesta $respuesta): self
    {
        if ($this->respuestas->removeElement($respuesta)) {
            // set the owning side to null (unless already changed)
            if ($respuesta->getUser() === $this) {
                $respuesta->setUser(null);
            }
        }

        return $this;
    }

 
    
}
