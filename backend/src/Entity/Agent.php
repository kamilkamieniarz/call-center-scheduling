<?php

namespace App\Entity;

use App\Repository\AgentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgentRepository::class)]
class Agent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private array $skills = [];

    #[ORM\Column]
    private array $availability = [];

    #[ORM\Column]
    private array $efficiency = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSkills(): array
    {
        return $this->skills;
    }

    public function setSkills(array $skills): static
    {
        $this->skills = $skills;

        return $this;
    }

    public function getAvailability(): array
    {
        return $this->availability;
    }

    public function setAvailability(array $availability): static
    {
        $this->availability = $availability;

        return $this;
    }

    public function getEfficiency(): array
    {
        return $this->efficiency;
    }

    public function setEfficiency(array $efficiency): static
    {
        $this->efficiency = $efficiency;

        return $this;
    }
}
