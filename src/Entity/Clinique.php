<?php

namespace App\Entity;

use App\Repository\CliniqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CliniqueRepository::class)
 */
class Clinique
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
     * @ORM\Column(type="text")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $rdvDomicile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $siret;

    /**
     * @ORM\OneToMany(targetEntity=CliniqueHoraire::class, mappedBy="clinique", orphanRemoval=true)
     */
    private $cliniqueHoraires;

    /**
     * @ORM\OneToMany(targetEntity=Veterinaire::class, mappedBy="clinique", orphanRemoval=true)
     */
    private $veterinaires;

    /**
     * @ORM\OneToMany(targetEntity=Secretaire::class, mappedBy="clinique", orphanRemoval=true, cascade={"persist"})
     */
    private $secretaires;

    /**
     * @ORM\Column(type="boolean")
     */
    private $demande = 0;

    public function __construct()
    {
        $this->cliniqueHoraires = new ArrayCollection();
        $this->veterinaires = new ArrayCollection();
        $this->secretaires = new ArrayCollection();
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getRdvDomicile(): ?bool
    {
        return $this->rdvDomicile;
    }

    public function setRdvDomicile(bool $rdvDomicile): self
    {
        $this->rdvDomicile = $rdvDomicile;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * @return Collection|CliniqueHoraire[]
     */
    public function getCliniqueHoraires(): Collection
    {
        return $this->cliniqueHoraires;
    }

    public function addCliniqueHoraire(CliniqueHoraire $cliniqueHoraire): self
    {
        if (!$this->cliniqueHoraires->contains($cliniqueHoraire)) {
            $this->cliniqueHoraires[] = $cliniqueHoraire;
            $cliniqueHoraire->setClinique($this);
        }

        return $this;
    }

    public function removeCliniqueHoraire(CliniqueHoraire $cliniqueHoraire): self
    {
        if ($this->cliniqueHoraires->contains($cliniqueHoraire)) {
            $this->cliniqueHoraires->removeElement($cliniqueHoraire);
            // set the owning side to null (unless already changed)
            if ($cliniqueHoraire->getClinique() === $this) {
                $cliniqueHoraire->setClinique(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Veterinaire[]
     */
    public function getVeterinaires(): Collection
    {
        return $this->veterinaires;
    }

    public function addVeterinaire(Veterinaire $veterinaire): self
    {
        if (!$this->veterinaires->contains($veterinaire)) {
            $this->veterinaires[] = $veterinaire;
            $veterinaire->setClinique($this);
        }

        return $this;
    }

    public function removeVeterinaire(Veterinaire $veterinaire): self
    {
        if ($this->veterinaires->contains($veterinaire)) {
            $this->veterinaires->removeElement($veterinaire);
            // set the owning side to null (unless already changed)
            if ($veterinaire->getClinique() === $this) {
                $veterinaire->setClinique(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Secretaire[]
     */
    public function getSecretaires(): Collection
    {
        return $this->secretaires;
    }

    public function addSecretaire(Secretaire $secretaire): self
    {
        if (!$this->secretaires->contains($secretaire)) {
            $this->secretaires[] = $secretaire;
            $secretaire->setClinique($this);
        }

        return $this;
    }

    public function removeSecretaire(Secretaire $secretaire): self
    {
        if ($this->secretaires->contains($secretaire)) {
            $this->secretaires->removeElement($secretaire);
            // set the owning side to null (unless already changed)
            if ($secretaire->getClinique() === $this) {
                $secretaire->setClinique(null);
            }
        }

        return $this;
    }

    public function getDemande(): ?bool
    {
        return $this->demande;
    }

    public function setDemande(bool $demande): self
    {
        $this->demande = $demande;

        return $this;
    }

    public function hasUser(User $user)
    {
        if ($this->getSecretaires()
                 ->contains($user))
            return true;
        if ($this->getVeterinaires()
                 ->contains($user))
            return true;

        return false;
    }

}
