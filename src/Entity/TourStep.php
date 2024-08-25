<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TourStepRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            requirements: ['id' => '\d+'],
            uriTemplate: '/tours/{id}/tour_steps',
            uriVariables: [
                'id' => new Link(
                    fromClass: Tour::class,
                    fromProperty: 'tourSteps',
                )
            ],
            normalizationContext: ['groups' => ['tourStep:read']],
            denormalizationContext: ['groups' => ['tourStep:read']],
        ),
        new Get(),
        new Post(),
        new Put(),
        new Patch(),
        new Delete(),
    ]
)]
#[ORM\Entity(repositoryClass: TourStepRepository::class)]
class TourStep
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tourStep:read'])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['tourStep:read'])]
    private ?\DateTimeImmutable $actualDate = null;

    #[ORM\Column]
    #[Groups(['tourStep:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['tourStep:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['tourStep:read'])]
    private ?\DateTimeImmutable $actualArrival = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['tourStep:read'])]
    private ?\DateTimeImmutable $actualDeparture = null;

    #[ORM\Column]
    #[Groups(['tourStep:read'])]
    private ?int $stepNumber = null;

    #[ORM\OneToOne(mappedBy: 'tourStep', cascade: ['persist', 'remove'])]
    #[Groups(['tourStep:read'])]
    private ?OrderStep $orderStep = null;

    #[ORM\ManyToOne(inversedBy: 'tourSteps')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['tourStep:read'])]
    private ?Tour $tour = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActualDate(): ?\DateTimeImmutable
    {
        return $this->actualDate;
    }

    public function setActualDate(?\DateTimeImmutable $actualDate): static
    {
        $this->actualDate = $actualDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getActualArrival(): ?\DateTimeImmutable
    {
        return $this->actualArrival;
    }

    public function setActualArrival(?\DateTimeImmutable $actualArrival): static
    {
        $this->actualArrival = $actualArrival;

        return $this;
    }

    public function getActualDeparture(): ?\DateTimeImmutable
    {
        return $this->actualDeparture;
    }

    public function setActualDeparture(?\DateTimeImmutable $actualDeparture): static
    {
        $this->actualDeparture = $actualDeparture;

        return $this;
    }

    public function getStepNumber(): ?int
    {
        return $this->stepNumber;
    }

    public function setStepNumber(int $stepNumber): static
    {
        $this->stepNumber = $stepNumber;

        return $this;
    }

    public function getOrderStep(): ?OrderStep
    {
        return $this->orderStep;
    }

    public function setOrderStep(?OrderStep $orderStep): static
    {
        // unset the owning side of the relation if necessary
        if ($orderStep === null && $this->orderStep !== null) {
            $this->orderStep->setTourStep(null);
        }

        // set the owning side of the relation if necessary
        if ($orderStep !== null && $orderStep->getTourStep() !== $this) {
            $orderStep->setTourStep($this);
        }

        $this->orderStep = $orderStep;

        return $this;
    }

    public function getTour(): ?Tour
    {
        return $this->tour;
    }

    public function setTour(?Tour $tour): static
    {
        $this->tour = $tour;

        return $this;
    }
}
