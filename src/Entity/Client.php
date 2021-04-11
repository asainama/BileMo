<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Since;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @UniqueEntity("email")
 * @UniqueEntity("name")
 * @ExclusionPolicy("all")
 */
class Client implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"public","list"})
     * @Expose()
     * @Serializer\Since("1.0")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Serializer\Groups({"public","list"})
     * @Assert\NotBlank(message="Le champs email est requis",groups={"CREATE"})
     * @Assert\Email(message="Le champs n'est pas de type email",groups={"CREATE"})
     * @Expose()
     * @Serializer\Since("1.0")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Serializer\Groups({"public","list"})
     * @Expose()
     * @Serializer\Since("1.0")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Le champs password est requis", groups={"CREATE"})
     * @Expose()
     * @Serializer\Since("1.0")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Serializer\Groups({"public","list"})
     * @Assert\NotBlank(message="Le champs name est requis", groups={"CREATE"})
     * @Expose()
     * @Serializer\Since("1.0")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="client", orphanRemoval=true, cascade={"persist"})
     * @Serializer\Since("1.0")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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
    public function getUsername(): string
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setClient($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getClient() === $this) {
                $user->setClient(null);
            }
        }

        return $this;
    }
}
