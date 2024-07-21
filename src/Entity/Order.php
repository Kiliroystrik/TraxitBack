<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['order:read']],
            denormalizationContext: ['groups' => ['order:write']]
        ),
        new Get(
            requirements: ['id' => '\d+'],
            normalizationContext: ['groups' => ['order:read']],
            denormalizationContext: ['groups' => ['order:read']],
        ),
        new Put(
            requirements: ['id' => '\d+'],
            normalizationContext: ['groups' => ['order:write']],
            denormalizationContext: ['groups' => ['order:write']],
        ),
        new Patch(
            requirements: ['id' => '\d+'],
            normalizationContext: ['groups' => ['order:write']],
            denormalizationContext: ['groups' => ['order:write']],
        ),
        new Delete(),
    ]
)]
#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['order:read', 'order:write'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['order:read', 'order:write'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['order:read', 'order:write'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order:read', 'order:write'])]
    private ?Company $company = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order:read', 'order:write'])]
    private ?Client $client = null;

    /**
     * @var Collection<int, OrderStep>
     */
    #[ORM\OneToMany(targetEntity: OrderStep::class, mappedBy: '_order', orphanRemoval: true)]
    #[Groups(['order:read', 'order:write'])]
    private Collection $orderSteps;

    public function __construct()
    {
        $this->orderSteps = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
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

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

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
            $orderStep->setOrder($this);
        }

        return $this;
    }

    public function removeOrderStep(OrderStep $orderStep): static
    {
        if ($this->orderSteps->removeElement($orderStep)) {
            // set the owning side to null (unless already changed)
            if ($orderStep->getOrder() === $this) {
                $orderStep->setOrder(null);
            }
        }

        return $this;
    }
}
