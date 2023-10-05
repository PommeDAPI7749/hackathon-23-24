<?php

namespace App\Entity;

use App\Repository\QuizzRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizzRepository::class)]
class Quizz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $data = null;

    #[ORM\Column(nullable: true)]
    private ?float $goodAnswer = null;

    #[ORM\Column(nullable: true)]
    private ?float $wrongAnswer = null;

    #[ORM\ManyToOne(inversedBy: 'Quizz')]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isFinished = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getGoodAnswer(): ?float
    {
        return $this->goodAnswer;
    }

    public function setGoodAnswer(?float $goodAnswer): static
    {
        $this->goodAnswer = $goodAnswer;

        return $this;
    }

    public function getWrongAnswer(): ?float
    {
        return $this->wrongAnswer;
    }

    public function setWrongAnswer(?float $wrongAnswer): static
    {
        $this->wrongAnswer = $wrongAnswer;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function isIsFinished(): ?bool
    {
        return $this->isFinished;
    }

    public function setIsFinished(?bool $isFinished): static
    {
        $this->isFinished = $isFinished;

        return $this;
    }
}
