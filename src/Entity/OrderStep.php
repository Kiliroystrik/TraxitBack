<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\OrderStepController;
use App\Repository\OrderStepRepository;
use App\State\OrderStepPostProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiFilter(OrderFilter::class, properties: ['position' => 'ASC'])]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['orderStep:read']],
            denormalizationContext: ['groups' => ['orderStep:write']],
        ),
        new GetCollection(
            requirements: ['id' => '\d+'],
            uriTemplate: '/orders/{id}/order_steps',
            uriVariables: [
                'id' => new Link(
                    fromClass: Order::class,
                    fromProperty: 'orderSteps',
                )
            ],
            order: ['position' => 'ASC'],
            normalizationContext: ['groups' => ['orderStep:read']],
            denormalizationContext: ['groups' => ['orderStep:read']],
        ),
        new Get(
            requirements: ['id' => '\d+'],
            normalizationContext: ['groups' => ['orderStep:read']],
            denormalizationContext: ['groups' => ['orderStep:read']],
        ),
        new Put(
            requirements: ['id' => '\d+'],
            normalizationContext: ['groups' => ['orderStep:write']],
            denormalizationContext: ['groups' => ['orderStep:write']],
        ),
        new Delete(),
    ]
)]
#[ORM\Entity(repositoryClass: OrderStepRepository::class)]
class OrderStep
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['order:read', 'order:write', 'orderStep:read', 'orderStep:write', 'tourStep:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['order:read', 'order:write', 'orderStep:read', 'orderStep:write', 'tourStep:read'])]
    private ?string $type = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['order:read', 'order:write', 'orderStep:read', 'orderStep:write', 'tourStep:read'])]
    private ?int $position = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['order:read', 'order:write', 'orderStep:read', 'orderStep:write', 'tourStep:read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['order:read', 'order:write', 'orderStep:read', 'orderStep:write', 'tourStep:read'])]
    private ?string $quantity = null;

    #[ORM\Column]
    #[Groups(['order:read', 'order:write', 'orderStep:read', 'orderStep:write', 'tourStep:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['order:read', 'order:write', 'orderStep:read', 'orderStep:write', 'tourStep:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['order:read', 'order:write', 'orderStep:read', 'orderStep:write', 'tourStep:read'])]
    private ?\DateTimeImmutable $scheduledArrival = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['order:read', 'order:write', 'orderStep:read', 'orderStep:write', 'tourStep:read'])]
    private ?\DateTimeImmutable $scheduledDeparture = null;

    #[ORM\ManyToOne(inversedBy: 'orderSteps', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order:read', 'order:write', 'orderStep:read', 'tourStep:read'])]
    private ?Address $address = null;

    #[ORM\ManyToOne(inversedBy: 'orderSteps')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['orderStep:read', 'orderStep:write', 'tourStep:read'])]
    private ?Order $_order = null;

    #[ORM\OneToOne(inversedBy: 'orderStep', cascade: ['persist', 'remove'])]
    #[Groups(['order:read', 'order:write', 'orderStep:read', 'orderStep:write',])]
    private ?TourStep $tourStep = null;

    #[ORM\ManyToOne(inversedBy: 'orderSteps')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order:read', 'order:write', 'orderStep:read', 'orderStep:write', 'tourStep:read'])]
    private ?Status $status = null;

    #[ORM\ManyToOne(inversedBy: 'orderSteps')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order:read', 'order:write', 'orderStep:read', 'orderStep:write', 'tourStep:read'])]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'orderSteps')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['order:read', 'order:write', 'orderStep:read', 'orderStep:write', 'tourStep:read'])]
    private ?Unit $unit = null;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): static
    {
        $this->quantity = $quantity;

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

    public function getScheduledArrival(): ?\DateTimeImmutable
    {
        return $this->scheduledArrival;
    }

    public function setScheduledArrival(?\DateTimeImmutable $scheduledArrival): static
    {
        $this->scheduledArrival = $scheduledArrival;

        return $this;
    }

    public function getScheduledDeparture(): ?\DateTimeImmutable
    {
        return $this->scheduledDeparture;
    }

    public function setScheduledDeparture(?\DateTimeImmutable $scheduledDeparture): static
    {
        $this->scheduledDeparture = $scheduledDeparture;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->_order;
    }

    public function setOrder(?Order $_order): static
    {
        $this->_order = $_order;

        return $this;
    }

    public function getTourStep(): ?TourStep
    {
        return $this->tourStep;
    }

    public function setTourStep(?TourStep $tourStep): static
    {
        $this->tourStep = $tourStep;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): static
    {
        $this->unit = $unit;

        return $this;
    }
}
