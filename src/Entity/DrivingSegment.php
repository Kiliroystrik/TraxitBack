<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DrivingSegmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: DrivingSegmentRepository::class)]
class DrivingSegment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\ManyToOne(inversedBy: 'drivingSegments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DriverAssignment $driverAssignment = null;

    #[ORM\ManyToOne(inversedBy: 'drivingSegments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?VehicleAssignment $vehicleAssignment = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDriverAssignment(): ?DriverAssignment
    {
        return $this->driverAssignment;
    }

    public function setDriverAssignment(?DriverAssignment $driverAssignment): static
    {
        $this->driverAssignment = $driverAssignment;

        return $this;
    }

    public function getVehicleAssignment(): ?VehicleAssignment
    {
        return $this->vehicleAssignment;
    }

    public function setVehicleAssignment(?VehicleAssignment $vehicleAssignment): static
    {
        $this->vehicleAssignment = $vehicleAssignment;

        return $this;
    }
}
