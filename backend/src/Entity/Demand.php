<?php

namespace App\Entity;

use App\Repository\DemandRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandRepository::class)]
class Demand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $queue = null;

    #[ORM\Column(length: 50)]
    private ?string $day = null;

    #[ORM\Column]
    private ?int $hour = null;

    #[ORM\Column]
    private ?int $predictedCalls = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQueue(): ?string
    {
        return $this->queue;
    }

    public function setQueue(string $queue): static
    {
        $this->queue = $queue;

        return $this;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): static
    {
        $this->day = $day;

        return $this;
    }

    public function getHour(): ?int
    {
        return $this->hour;
    }

    public function setHour(int $hour): static
    {
        $this->hour = $hour;

        return $this;
    }

    public function getPredictedCalls(): ?int
    {
        return $this->predictedCalls;
    }

    public function setPredictedCalls(int $predictedCalls): static
    {
        $this->predictedCalls = $predictedCalls;

        return $this;
    }
}
