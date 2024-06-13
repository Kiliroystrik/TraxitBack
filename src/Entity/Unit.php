<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UnitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: UnitRepository::class)]
class Unit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $name = null;

    /**
     * @var Collection<int, OrderStep>
     */
    #[ORM\OneToMany(targetEntity: OrderStep::class, mappedBy: 'unit')]
    private Collection $orderSteps;

    public function __construct()
    {
        $this->orderSteps = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, OrderStep>
     */
    public function getOrderSteps(): Collection
    {
        return $this->orderSteps;
    }

    public function addOrderStep(OrderStep $orderStep): static
    {
        if (!$this->orderSteps->contains($orderStep)) {
            $this->orderSteps->add($orderStep);
            $orderStep->setUnit($this);
        }

        return $this;
    }

    public function removeOrderStep(OrderStep $orderStep): static
    {
        if ($this->orderSteps->removeElement($orderStep)) {
            // set the owning side to null (unless already changed)
            if ($orderStep->getUnit() === $this) {
                $orderStep->setUnit(null);
            }
        }

        return $this;
    }
}
