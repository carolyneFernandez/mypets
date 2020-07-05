<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ConsultationRepository::class)
 */
class Consultation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Veterinaire::class, inversedBy="consultations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $veterinaire;

    /**
     * @ORM\ManyToOne(targetEntity=Animal::class, inversedBy="consultations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $animal;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $compteRendu;

    /**
     * @ORM\OneToOne(targetEntity=Rdv::class, inversedBy="consultation", cascade={"persist", "remove"})
     */
    private $rdv;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * Consultation constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->dateCreation = new \DateTime('today');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVeterinaire(): ?Veterinaire
    {
        return $this->veterinaire;
    }

    public function setVeterinaire(?Veterinaire $veterinaire): self
    {
        $this->veterinaire = $veterinaire;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): self
    {
        $this->animal = $animal;

        return $this;
    }

    public function getCompteRendu(): ?string
    {
        return $this->compteRendu;
    }

    public function setCompteRendu(?string $compteRendu): self
    {
        $this->compteRendu = $compteRendu;

        return $this;
    }

    public function getRdv(): ?Rdv
    {
        return $this->rdv;
    }

    public function setRdv(?Rdv $rdv): self
    {
        $this->rdv = $rdv;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

}
