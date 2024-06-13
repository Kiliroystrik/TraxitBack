<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $registrationNumber = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $mileage = null;

    #[ORM\Column]
    private ?bool $isAvailable = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $registeredAt = null;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    /**
     * @var Collection<int, VehicleAssignment>
     */
    #[ORM\OneToMany(targetEntity: VehicleAssignment::class, mappedBy: 'vehicle', orphanRemoval: true)]
    private Collection $vehicleAssignments;

    // #[ORM\ManyToOne(inversedBy: 'vehicles')]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?Model $model = null;

    /**
     * @var Collection<int, FuelType>
     */
    #[ORM\ManyToMany(targetEntity: FuelType::class, inversedBy: 'vehicles')]
    private Collection $fuelTypes;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\OneToMany(targetEntity: Service::class, mappedBy: 'vehicle', orphanRemoval: true)]
    private Collection $services;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?VehicleModel $model = null;

    public function __construct()
    {
        $this->vehicleAssignments = new ArrayCollection();
        $this->fuelTypes = new ArrayCollection();
        $this->services = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegistrationNumber(): ?string
    {
        return $this->registrationNumber;
    }

    public function setRegistrationNumber(string $registrationNumber): static
    {
        $this->registrationNumber = $registrationNumber;

        return $this;
    }

    public function getMileage(): ?string
    {
        return $this->mileage;
    }

    public function setMileage(string $mileage): static
    {
        $this->mileage = $mileage;

        return $this;
    }


    public function getisAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setisAvailable(bool $isAvailable): static
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeImmutable $registeredAt): static
    {
        $this->registeredAt = $registeredAt;

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
     * @return Collection<int, VehicleAssignment>
     */
    public function getVehicleAssignments(): Collection
    {
        return $this->vehicleAssignments;
    }

    public function addVehicleAssignment(VehicleAssignment $vehicleAssignment): static
    {
        if (!$this->vehicleAssignments->contains($vehicleAssignment)) {
            $this->vehicleAssignments->add($vehicleAssignment);
            $vehicleAssignment->setVehicle($this);
        }

        return $this;
    }

    public function removeVehicleAssignment(VehicleAssignment $vehicleAssignment): static
    {
        if ($this->vehicleAssignments->removeElement($vehicleAssignment)) {
            // set the owning side to null (unless already changed)
            if ($vehicleAssignment->getVehicle() === $this) {
                $vehicleAssignment->setVehicle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FuelType>
     */
    public function getFuelTypes(): Collection
    {
        return $this->fuelTypes;
    }

    public function addFuelType(FuelType $fuelType): static
    {
        if (!$this->fuelTypes->contains($fuelType)) {
            $this->fuelTypes->add($fuelType);
        }

        return $this;
    }

    public function removeFuelType(FuelType $fuelType): static
    {
        $this->fuelTypes->removeElement($fuelType);

        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setVehicle($this);
        }

        return $this;
    }

    public function removeService(Service $service): static
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getVehicle() === $this) {
                $service->setVehicle(null);
            }
        }

        return $this;
    }

    public function getModel(): ?VehicleModel
    {
        return $this->model;
    }

    public function setModel(?VehicleModel $model): static
    {
        $this->model = $model;

        return $this;
    }
}
