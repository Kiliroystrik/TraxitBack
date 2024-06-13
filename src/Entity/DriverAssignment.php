<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DriverAssignmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: DriverAssignmentRepository::class)]
class DriverAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?array $role = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $endDate = null;

    /**
     * @var Collection<int, DrivingSegment>
     */
    #[ORM\OneToMany(targetEntity: DrivingSegment::class, mappedBy: 'driverAssignment', orphanRemoval: true)]
    private Collection $drivingSegments;

    #[ORM\ManyToOne(inversedBy: 'driverAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tour $tour = null;

    #[ORM\ManyToOne(inversedBy: 'driverAssignments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Driver $driver = null;

    public function __construct()
    {
        $this->drivingSegments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?array
    {
        return $this->role;
    }

    public function setRole(?array $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection<int, DrivingSegment>
     */
    public function getDrivingSegments(): Collection
    {
        return $this->drivingSegments;
    }

    public function addDrivingSegment(DrivingSegment $drivingSegment): static
    {
        if (!$this->drivingSegments->contains($drivingSegment)) {
            $this->drivingSegments->add($drivingSegment);
            $drivingSegment->setDriverAssignment($this);
        }

        return $this;
    }

    public function removeDrivingSegment(DrivingSegment $drivingSegment): static
    {
        if ($this->drivingSegments->removeElement($drivingSegment)) {
            // set the owning side to null (unless already changed)
            if ($drivingSegment->getDriverAssignment() === $this) {
                $drivingSegment->setDriverAssignment(null);
            }
        }

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

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(?Driver $driver): static
    {
        $this->driver = $driver;

        return $this;
    }
}
