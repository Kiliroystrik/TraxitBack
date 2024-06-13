<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, OrderStep>
     */
    #[ORM\OneToMany(targetEntity: OrderStep::class, mappedBy: 'product')]
    private Collection $orderSteps;

    /**
     * @var Collection<int, TourType>
     */
    #[ORM\ManyToMany(targetEntity: TourType::class, inversedBy: 'products')]
    private Collection $tourTypes;

    public function __construct()
    {
        $this->orderSteps = new ArrayCollection();
        $this->tourTypes = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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
            $orderStep->setProduct($this);
        }

        return $this;
    }

    public function removeOrderStep(OrderStep $orderStep): static
    {
        if ($this->orderSteps->removeElement($orderStep)) {
            // set the owning side to null (unless already changed)
            if ($orderStep->getProduct() === $this) {
                $orderStep->setProduct(null);
            }
        }

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
}
