<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiFilter(OrderFilter::class, properties: ['serialNumber', 'client', 'tour', 'createdAt', 'updatedAt'])]
#[ApiFilter(DateFilter::class, properties: ['createdAt', 'updatedAt'])]
#[ApiResource(
    operations: [
        new GetCollection(
            security: "is_granted('VIEW', object)",
            normalizationContext: ['groups' => ['order:read']],
            denormalizationContext: ['groups' => ['order:write']],
        ),
        new Get(
            security: "is_granted('VIEW', object)",
            requirements: ['id' => '\d+'],
            normalizationContext: ['groups' => ['order:read']],
            denormalizationContext: ['groups' => ['order:read']],
        ),
        new Post(
            securityPostDenormalize: "is_granted('CREATE', object)",
            requirements: ['id' => '\d+'],
            normalizationContext: ['groups' => ['order:write']],
            denormalizationContext: ['groups' => ['order:write']],
        ),
        new Put(
            security: "is_granted('EDIT', object)",
            requirements: ['id' => '\d+'],
            normalizationContext: ['groups' => ['order:write']],
            denormalizationContext: ['groups' => ['order:write']],
        ),
        new Patch(
            security: "is_granted('EDIT', object)",
            requirements: ['id' => '\d+'],
            normalizationContext: ['groups' => ['order:write']],
            denormalizationContext: ['groups' => ['order:write']],
        ),
        new Delete(
            security: "is_granted('DELETE', object)",
        ),
    ]
)]
#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order implements CompanyAwareInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['order:read', 'order:write'])]
    private ?int $id = null;

    #[ORM\Column(nullable: false)]
    #[Groups(['order:read', 'order:write'])]
    private string $serialNumber;

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
    #[ORM\OneToMany(targetEntity: OrderStep::class, mappedBy: '_order', orphanRemoval: true, cascade: ['persist', 'remove'])]
    // #[Groups(['order:read', 'order:write'])]
    private Collection $orderSteps;

    public function __construct()
    {
        $this->orderSteps = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();

        $this->initializeSerialNumber();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSerialNumber(): ?string
    {
        return $this->serialNumber;
    }

    public function setSerialNumber(string $serialNumber): static
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    public function initializeSerialNumber(): self
    {
        // Je définis un nouveau serial number, unique et non nul
        // Il  prend pour racine "ORD", suivi d'un réprésentant son timestamp, suivi d'un chiffre aleatoire
        $date = new \DateTimeImmutable();

        $this->serialNumber = 'ORD' . $date->format('YmdHis') . rand(1000, 9999);

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
