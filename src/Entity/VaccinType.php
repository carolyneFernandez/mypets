<?php

namespace App\Entity;

use App\Repository\VaccinTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=VaccinTypeRepository::class)
 */
class VaccinType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=AnimalType::class, inversedBy="vaccinTypes")
     */
    private $animalType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $primovaccination;

    /**
     * @ORM\Column(type="dateinterval")
     */
    private $rappel;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Vaccin::class, mappedBy="vaccinType", orphanRemoval=true)
     */
    private $vaccins;

    public function __construct()
    {
        $this->vaccins = new ArrayCollection();
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

    public function getAnimalType(): ?AnimalType
    {
        return $this->animalType;
    }

    public function setAnimalType(?AnimalType $animalType): self
    {
        $this->animalType = $animalType;

        return $this;
    }

    public function getPrimovaccination(): ?string
    {
        return $this->primovaccination;
    }

    public function setPrimovaccination(?string $primovaccination): self
    {
        $this->primovaccination = $primovaccination;

        return $this;
    }

    public function getRappel(): ?\DateInterval
    {
        return $this->rappel;
    }

    public function setRappel(\DateInterval $rappel): self
    {
        $this->rappel = $rappel;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Vaccin[]
     */
    public function getVaccins(): Collection
    {
        return $this->vaccins;
    }

    public function addVaccin(Vaccin $vaccin): self
    {
        if (!$this->vaccins->contains($vaccin)) {
            $this->vaccins[] = $vaccin;
            $vaccin->setVaccinType($this);
        }

        return $this;
    }

    public function removeVaccin(Vaccin $vaccin): self
    {
        if ($this->vaccins->contains($vaccin)) {
            $this->vaccins->removeElement($vaccin);
            // set the owning side to null (unless already changed)
            if ($vaccin->getVaccinType() === $this) {
                $vaccin->setVaccinType(null);
            }
        }

        return $this;
    }

}
