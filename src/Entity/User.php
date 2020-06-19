<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     itemOperations={},
 *     collectionOperations={
 *          "apilogin"={
 *              "method"="POST",
 *              "path"="/login",
 *              "controller"=API\APIIndexController::class,
 *              "normalization_context"={"groups"={"afup"}},
 *              "defaults"={"_api_receive"=false},
 *              "swagger_context"={
 *                  "summary" = "Login",
 *                  "parameters"={
 *                      {
 *                          "name" = "User",
 *                          "in" = "body",
 *                          "schema" = {
 *                              "type" = "object",
 *                              "properties" = {
 *                                  "password" = {"type"="string"},
 *                                  "email" = {"type"="string"},
 *                              }
 *                           },
 *                          "required" = "true",
 *                      }
 *                  },
 *              }
 *          }
 *      }
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string", length=30)
 * @ORM\DiscriminatorMap({
 *     "veterinaire"="Veterinaire",
 *     "proprietaire"="Proprietaire",
 *     "secretaire"="Secretaire",
 *     "user"="User"
 * })
 * @UniqueEntity(fields={"email"}, message="Cette email est déjà utilisée par à un autre utilisateur")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"user:afup"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"user:afup"})
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="boolean", options={"default"=1})
     */
    private $actif = true;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $derniereConnexion;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    private $apiToken;

    /**
     * User constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->dateCreation = new \DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function addRole($role)
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole($role)
    {
        if (($key = array_search($role, $this->roles)) !== false) {
            unset($this->roles[$key]);
        }

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getDerniereConnexion(): ?\DateTimeInterface
    {
        return $this->derniereConnexion;
    }

    public function setDerniereConnexion(?\DateTimeInterface $derniereConnexion): self
    {
        $this->derniereConnexion = $derniereConnexion;

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNomPrenom()
    {
        return $this->getNom() . ' ' . $this->getPrenom();
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

}
