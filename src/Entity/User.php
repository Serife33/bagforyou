<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 190)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $Username = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, Bag>
     */
    #[ORM\OneToMany(targetEntity: Bag::class, mappedBy: 'user')]
    private Collection $borrower;

    /**
     * @var Collection<int, Bag>
     */
    #[ORM\OneToMany(targetEntity: Bag::class, mappedBy: 'owner')]
    private Collection $owner;

   

    public function __construct()
    {
        $this->borrower = new ArrayCollection();
        $this->owner = new ArrayCollection();
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    /**
     * @return Collection<int, Bag>
     */
    public function getBorrower(): Collection
    {
        return $this->borrower;
    }

    public function addBorrower(Bag $borrower): static
    {
        if (!$this->borrower->contains($borrower)) {
            $this->borrower->add($borrower);
            $borrower->setUser($this);
        }

        return $this;
    }

    public function removeBorrower(Bag $borrower): static
    {
        if ($this->borrower->removeElement($borrower)) {
            // set the owning side to null (unless already changed)
            if ($borrower->getUser() === $this) {
                $borrower->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Bag>
     */
    public function getOwner(): Collection
    {
        return $this->owner;
    }

    public function addOwner(Bag $owner): static
    {
        if (!$this->owner->contains($owner)) {
            $this->owner->add($owner);
            $owner->setOwner($this);
        }

        return $this;
    }

    public function removeOwner(Bag $owner): static
    {
        if ($this->owner->removeElement($owner)) {
            // set the owning side to null (unless already changed)
            if ($owner->getOwner() === $this) {
                $owner->setOwner(null);
            }
        }

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    public function setUsername(string $Username): static
    {
        $this->Username = $Username;

        return $this;
    }
}
