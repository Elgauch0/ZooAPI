<?php

namespace App\Entity;


use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;




#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['show_product'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['show_product'])]
    #[Assert\NotBlank(message: "Le titre du livre est obligatoire")]
    private ?string $username = null;

    #[ORM\Column(length: 50)]
    #[Groups(['show_product'])]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    #[Groups(['show_product'])]
    private ?string $prénom = null;

    /**
     * @var Collection<int, Role>
     */
    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    #[Ignore]
    private Collection $role;

    /**
     * @var Collection<int, RapportVeterinaire>
     */
    #[ORM\OneToMany(targetEntity: RapportVeterinaire::class, mappedBy: 'veterinaire')]
    #[Ignore]
    private Collection $rapport;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    public function __construct()
    {
        $this->role = new ArrayCollection();
        $this->rapport = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrénom(): ?string
    {
        return $this->prénom;
    }

    public function setPrénom(string $prénom): static
    {
        $this->prénom = $prénom;

        return $this;
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRole(): Collection
    {
        return $this->role;
    }

    public function addRole(Role $role): static
    {
        if (!$this->role->contains($role)) {
            $this->role->add($role);
        }

        return $this;
    }

    public function removeRole(Role $role): static
    {
        $this->role->removeElement($role);

        return $this;
    }

    /**
     * @return Collection<int, RapportVeterinaire>
     */
    public function getRapport(): Collection
    {
        return $this->rapport;
    }

    public function addRapport(RapportVeterinaire $rapport): static
    {
        if (!$this->rapport->contains($rapport)) {
            $this->rapport->add($rapport);
            $rapport->setVeterinaire($this);
        }

        return $this;
    }

    public function removeRapport(RapportVeterinaire $rapport): static
    {
        if ($this->rapport->removeElement($rapport)) {
            // set the owning side to null (unless already changed)
            if ($rapport->getVeterinaire() === $this) {
                $rapport->setVeterinaire(null);
            }
        }

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'nom' => $this->nom,
            'prénom' => $this->prénom,

        ];
    }
}
