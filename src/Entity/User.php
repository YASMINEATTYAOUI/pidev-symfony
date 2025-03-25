<?php
namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "user")]
class User implements UserInterface , PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $resetToken = null;

    #[ORM\Column(type: "string", length: 100, unique: true)]
    private ?string $username = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $password = null;

    #[ORM\OneToOne(targetEntity: Citizen::class, cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(name: "citizen_id", referencedColumnName: "id", nullable: true)]
    private ?Citizen $citizen = null;

    #[ORM\OneToOne(targetEntity: Admin::class, cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(name: "admin_id", referencedColumnName: "id", nullable: true)]
    private ?Admin $admin = null;

    #[ORM\OneToOne(targetEntity: Agent::class, cascade: ["persist", "remove"])]
    #[ORM\JoinColumn(name: "agent_id", referencedColumnName: "id", nullable: true)]
    private ?Agent $agent = null;

    



    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;
        return $this;
    }
    
    public function getUserIdentifier(): string
{
    return $this->username;
}
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getCitizen(): ?Citizen
    {
        return $this->citizen;
    }

    public function setCitizen(?Citizen $citizen): self
    {
        $this->citizen = $citizen;
        return $this;
    }

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): self
    {
        $this->admin = $admin;
        return $this;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {
        $this->agent = $agent;
        return $this;
    }

    public function getRoles(): array
    {
       if ($this->admin != null ) {
           return ['ROLE_ADMIN'];
       } else if ($this->agent  != null){
    return ['ROLE_AGENT'];
    }
       else if ($this-> citizen  != null){
           return ['ROLE_CITIZEN'];
       }
       else return ['ROLE_USER'];
    }

    public function getSalt()
    {
        return null; // Not needed when using modern hashing algorithms
    }

    public function eraseCredentials()
    {
        // If you store any temporary data, such as plain-password, clear it here
    }
}
