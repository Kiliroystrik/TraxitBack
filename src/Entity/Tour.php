<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TourRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: TourRepository::class)]
class Tour
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'tours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    /**
     * @var Collection<int, TourStep>
     */
    #[ORM\OneToMany(targetEntity: TourStep::class, mappedBy: 'tour', orphanRemoval: true)]
    private Collection $tourSteps;

    /**
     * @var Collection<int, DriverAssignment>
     */
    #[ORM\OneToMany(targetEntity: DriverAssignment::class, mappedBy: 'tour', orphanRemoval: true)]
    private Collection $driverAssignments;

    /**
     * @var Collection<int, VehicleAssignment>
     */
    #[ORM\OneToMany(targetEntity: VehicleAssignment::class, mappedBy: 'tour', orphanRemoval: true)]
    private Collection $vehicleAssignment;

    public function __construct()
    {
        $this->tourSteps = new ArrayCollection();
        $this->driverAssignments = new ArrayCollection();
        $this->vehicleAssignment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeImmutable $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection<int, TourStep>
     */
    public function getTourSteps(): Collection
    {
        return $this->tourSteps;
    }

    public function addTourStep(TourStep $tourStep): static
    {
        if (!$this->tourSteps->contains($tourStep)) {
            $this->tourSteps->add($tourStep);
            $tourStep->setTour($this);
        }

        return $this;
    }

    public function removeTourStep(TourStep $tourStep): static
    {
        if ($this->tourSteps->removeElement($tourStep)) {
            // set the owning side to null (unless already changed)
            if ($tourStep->getTour() === $this) {
                $tourStep->setTour(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DriverAssignment>
     */
    public function getDriverAssignments(): Collection
    {
        return $this->driverAssignments;
    }

    public function addDriverAssignment(DriverAssignment $driverAssignment): static
    {
        if (!$this->driverAssignments->contains($driverAssignment)) {
            $this->driverAssignments->add($driverAssignment);
            $driverAssignment->setTour($this);
        }

        return $this;
    }

    public function removeDriverAssignment(DriverAssignment $driverAssignment): static
    {
        if ($this->driverAssignments->removeElement($driverAssignment)) {
            // set the owning side to null (unless already changed)
            if ($driverAssignment->getTour() === $this) {
                $driverAssignment->setTour(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, VehicleAssignment>
     */
    public function getVehicleAssignment(): Collection
    {
        return $this->vehicleAssignment;
    }

    public function addVehicleAssignment(VehicleAssignment $vehicleAssignment): static
    {
        if (!$this->vehicleAssignment->contains($vehicleAssignment)) {
            $this->vehicleAssignment->add($vehicleAssignment);
            $vehicleAssignment->setTour($this);
        }

        return $this;
    }

    public function removeVehicleAssignment(VehicleAssignment $vehicleAssignment): static
    {
        if ($this->vehicleAssignment->removeElement($vehicleAssignment)) {
            // set the owning side to null (unless already changed)
            if ($vehicleAssignment->getTour() === $this) {
                $vehicleAssignment->setTour(null);
            }
        }

        return $this;
    }
}
