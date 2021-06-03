<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Email(message="Cet email '{{ value }}' n'est pas valide.")
     * @Assert\NotBlank(message="L'email est obligatoire")
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @Assert\Length (
     *     min=6, minMessage="Le mot de passe doit faire au moins 6 caratères",
     *     max=4096, maxMessage="Le mot de passe doit faire au max 4096 caratères"
     * )
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank(message="Le nom est obligatoire")
     * @Assert\Length(
     *     min=2, minMessage="Min 2 caractères",
     *     max=30, maxMessage="Max 30 caractères"
     * )
     * @ORM\Column(type="string", length=30)
     */
    private $nom;

    /**
     * @Assert\NotBlank(message="Le prénom est obligatoire")
     * @Assert\Length(
     *     min=2, minMessage="Min 2 caractères",
     *     max=30, maxMessage="Max 30 caractères"
     * )
     * @ORM\Column(type="string", length=30)
     */
    private $prenom;

    /**
     * @Assert\Length(
     *     min=10, minMessage="Le numéro de téléphone doit faire 10 chiffres",
     *     max=10, maxMessage="Le numéro de téléphone doit faire 10 chiffres"
     * )
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $telephone;


    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @Assert\NotBlank(message="Le site de rattachement est obligatoire")
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="organisateur")
     */
    private $sortiesOrganisateur;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, mappedBy="participants")
     */
    private $sortiesUser;

    /**
     * @Assert\NotBlank(message="Le pseudo est obligatoire")
     * @Assert\Length(
     *     min=2, minMessage="Min 2 caractères",
     *     max=30, maxMessage="Max 30 caractères"
     * )
     * @ORM\Column(type="string", length=30)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photoProfil;

    public function __construct()
    {
        $this->sortiesOrganisateur = new ArrayCollection();
        $this->sortiesUser = new ArrayCollection();
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
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
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
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

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

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site): void
    {
        $this->site = $site;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortiesOrganisateur(): Collection
    {
        return $this->sortiesOrganisateur;
    }

    public function addSortiesOrganisateur(Sortie $sortiesOrganisateur): self
    {
        if (!$this->sortiesOrganisateur->contains($sortiesOrganisateur)) {
            $this->sortiesOrganisateur[] = $sortiesOrganisateur;
            $sortiesOrganisateur->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortiesOrganisateur(Sortie $sortiesOrganisateur): self
    {
        if ($this->sortiesOrganisateur->removeElement($sortiesOrganisateur)) {
            // set the owning side to null (unless already changed)
            if ($sortiesOrganisateur->getOrganisateur() === $this) {
                $sortiesOrganisateur->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSortiesUser(): Collection
    {
        return $this->sortiesUser;
    }

    public function addSortiesUser(Sortie $sortiesUser): self
    {
        if (!$this->sortiesUser->contains($sortiesUser)) {
            $this->sortiesUser[] = $sortiesUser;
            $sortiesUser->addUser($this);
        }

        return $this;
    }

    public function removeSortiesUser(Sortie $sortiesUser): self
    {
        if ($this->sortiesUser->removeElement($sortiesUser)) {
            $sortiesUser->removeUser($this);
        }

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPhotoProfil(): ?string
    {
        return $this->photoProfil;
    }

    public function setPhotoProfil(?string $photoProfil): self
    {
        $this->photoProfil = $photoProfil;

        return $this;
    }
}
