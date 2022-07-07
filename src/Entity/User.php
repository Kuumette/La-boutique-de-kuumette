<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Un compte avec ce mail existe déjà')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    /**
     * Ne fais pas confiance à l'uilisateur
     */
    #[Assert\Email(
        message: 'L\'email n\'est pas valide',
    )]
    #[Assert\NotBlank(
        message: 'Merci de renseigner un email'
    )]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    /**
     * regex qui verifie si mon mot de passe contient bien min 10 caractères 1 minuscule 1 majuscule 1 chiffre 1 caractères spécial
     */
    #[Assert\Regex(
        pattern: '/^(?=.{10,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$/i',
        htmlPattern: '^(?=.{10,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$',
        message: 'Le mot de passe n\'est pas valide',
    )]
    #[ORM\Column(type: 'string')]
    private $password;

    #[Assert\NotBlank(
        message: 'Merci de renseigner un prénom'
    )]
    #[ORM\Column(type: 'string', length: 50)]
    private $firstname;

    #[Assert\NotBlank(
        message: 'Merci de renseigner un nom'
    )]
    #[ORM\Column(type: 'string', length: 50)]
    private $lastname;

    #[Assert\Regex(
        pattern: '/(0|(\\+33)|(0033))[1-9][0-9]{8}/i',
        htmlPattern: '(0|(\\+33)|(0033))[1-9][0-9]{8}',
        message: 'Le numéro de téléphone n\'est pas valide',
    )]
    #[Assert\NotBlank(
        message: 'Merci de renseigner un numéro de téléphone'
    )]
    #[ORM\Column(type: 'string', length: 10)]
    private $phone;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Order::class)]
    private $cart;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Logement::class)]
    private $logement;

    public function __construct()
    {
        $this->cart = new ArrayCollection();
        $this->logement = new ArrayCollection();
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, order>
     */
    public function getCart(): Collection
    {
        return $this->cart;
    }

    public function addCart(order $cart): self
    {
        if (!$this->cart->contains($cart)) {
            $this->cart[] = $cart;
            $cart->setUser($this);
        }

        return $this;
    }

    public function removeCart(order $cart): self
    {
        if ($this->cart->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getUser() === $this) {
                $cart->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, logement>
     */
    public function getLogement(): Collection
    {
        return $this->logement;
    }

    public function addLogement(logement $logement): self
    {
        if (!$this->logement->contains($logement)) {
            $this->logement[] = $logement;
            $logement->setUser($this);
        }

        return $this;
    }

    public function removeLogement(logement $logement): self
    {
        if ($this->logement->removeElement($logement)) {
            // set the owning side to null (unless already changed)
            if ($logement->getUser() === $this) {
                $logement->setUser(null);
            }
        }

        return $this;
    }
    public function __toString() 
    {
        return $this->getFirstname();
    }
}
