<?php

namespace App\Entity;

use App\Entity\Animal;
use App\Repository\AnimalTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *
 *     normalizationContext={"groups"={"animalType:read"}},
 *     denormalizationContext={"groups"={"animalType:write"}},
 *     collectionOperations={
 *          "get",
 *          "post"={"security"="is_granted('ROLE_USER')"}
 *     },
 *     itemOperations={"get","put"}
 * )
 * @ORM\Entity(repositoryClass=AnimalTypeRepository::class)
 */
class AnimalType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"animalType:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"animalType:read", "animalType:write"})
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Animal::class, mappedBy="type", orphanRemoval=true)
     * @Groups({"animalType:read"})
     */
    private $animals;

    /**
     * @ORM\OneToMany(targetEntity=VaccinType::class, mappedBy="animalType")
     * @Groups({"animalType:read"})
     */
    private $vaccinTypes;

    public function __construct()
    {
        $this->animals = new ArrayCollection();
        $this->vaccinTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Animal[]
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal): self
    {
        if (!$this->animals->contains($animal)) {
            $this->animals[] = $animal;
            $animal->setType($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animals->contains($animal)) {
            $this->animals->removeElement($animal);
            // set the owning side to null (unless already changed)
            if ($animal->getType() === $this) {
                $animal->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|VaccinType[]
     */
    public function getVaccinTypes(): Collection
    {
        return $this->vaccinTypes;
    }

    public function addVaccinType(VaccinType $vaccinType): self
    {
        if (!$this->vaccinTypes->contains($vaccinType)) {
            $this->vaccinTypes[] = $vaccinType;
            $vaccinType->setAnimalType($this);
        }

        return $this;
    }

    public function removeVaccinType(VaccinType $vaccinType): self
    {
        if ($this->vaccinTypes->contains($vaccinType)) {
            $this->vaccinTypes->removeElement($vaccinType);
            // set the owning side to null (unless already changed)
            if ($vaccinType->getAnimalType() === $this) {
                $vaccinType->setAnimalType(null);
            }
        }

        return $this;
    }

}
