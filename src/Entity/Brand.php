<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: BrandRepository::class)]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'brands')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    /**
     * @var Collection<int, VehicleModel>
     */
    #[ORM\OneToMany(targetEntity: VehicleModel::class, mappedBy: 'brand', orphanRemoval: true)]
    private Collection $models;

    // /**
    //  * @var Collection<int, Model>
    //  */
    // #[ORM\OneToMany(targetEntity: Model::class, mappedBy: 'brand', orphanRemoval: true)]
    // private Collection $models;

    public function __construct()
    {
        // $this->models = new ArrayCollection();
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

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    // /**
    //  * @return Collection<int, Model>
    //  */
    // public function getModels(): Collection
    // {
    //     return $this->models;
    // }

    // public function addModel(Model $model): static
    // {
    //     if (!$this->models->contains($model)) {
    //         $this->models->add($model);
    //         $model->setBrand($this);
    //     }

    //     return $this;
    // }

    // public function removeModel(Model $model): static
    // {
    //     if ($this->models->removeElement($model)) {
    //         // set the owning side to null (unless already changed)
    //         if ($model->getBrand() === $this) {
    //             $model->setBrand(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, VehicleModel>
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    public function addModel(VehicleModel $model): static
    {
        if (!$this->models->contains($model)) {
            $this->models->add($model);
            $model->setBrand($this);
        }

        return $this;
    }

    public function removeModel(VehicleModel $model): static
    {
        if ($this->models->removeElement($model)) {
            // set the owning side to null (unless already changed)
            if ($model->getBrand() === $this) {
                $model->setBrand(null);
            }
        }

        return $this;
    }
}
