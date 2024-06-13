<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DriverRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: DriverRepository::class)]
class Driver extends User
{

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $licenceNumber = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $licenceExpiration = null;

    #[ORM\Column]
    private ?bool $isAvailable = null;

    /**
     * @var Collection<int, TourType>
     */
    #[ORM\ManyToMany(targetEntity: TourType::class, inversedBy: 'drivers')]
    private Collection $tourTypes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $card = null;

    /**
     * @var Collection<int, DriverAssignment>
     */
    #[ORM\OneToMany(targetEntity: DriverAssignment::class, mappedBy: 'driver', orphanRemoval: true)]
    private Collection $driverAssignments;

    public function __construct()
    {
        // Set the role of the user to "ROLE_DRIVER"
        $this->setRoles(['ROLE_DRIVER']);
        $this->tourTypes = new ArrayCollection();
        $this->driverAssignments = new ArrayCollection();
    }

    public function getLicenceNumber(): ?string
    {
        return $this->licenceNumber;
    }

    public function setLicenceNumber(?string $licenceNumber): static
    {
        $this->licenceNumber = $licenceNumber;

        return $this;
    }

    public function getLicenceExpiration(): ?\DateTimeImmutable
    {
        return $this->licenceExpiration;
    }

    public function setLicenceExpiration(?\DateTimeImmutable $licenceExpiration): static
    {
        $this->licenceExpiration = $licenceExpiration;

        return $this;
    }

    public function getIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function setIsAvailable(bool $isAvailable): static
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    /**
     * @return Collection<int, TourType>
     */
    public function getTourTypes(): Collection
    {
        return $this->tourTypes;
    }

    public function addTourType(TourType $tourType): static
    {
        if (!$this->tourTypes->contains($tourType)) {
            $this->tourTypes->add($tourType);
        }

        return $this;
    }

    public function removeTourType(TourType $tourType): static
    {
        $this->tourTypes->removeElement($tourType);

        return $this;
    }


    public function getCard(): ?string
    {
        return $this->card;
    }

    public function setCard(?string $card): static
    {
        $this->card = $card;

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
            $driverAssignment->setDriver($this);
        }

        return $this;
    }

    public function removeDriverAssignment(DriverAssignment $driverAssignment): static
    {
        if ($this->driverAssignments->removeElement($driverAssignment)) {
            // set the owning side to null (unless already changed)
            if ($driverAssignment->getDriver() === $this) {
                $driverAssignment->setDriver(null);
            }
        }

        return $this;
    }
}
