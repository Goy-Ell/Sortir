<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank (message= "Veuillez remplir ce champ avant de valider !")
     * @Assert\Length (
     *     min=10,
     *     max=255,
     *     minMessage="Minimum 10 caractères svp !",
     *     maxMessage="Maximum 255 caractères svp !"
     *  )
     * @ORM\Column(type="string", length=50)
     */
    private $nom;

    /**
     * @Assert\NotBlank
     * @Assert\Type("DateTimeInterface")
     * @Assert\GreaterThan (propertyPath="dateLimiteInscription", message="La date de la sortie doit être supérieure à la date limite d'inscription !")
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDebut;

    /**
     * @Assert\NotBlank
     * @Assert\GreaterThan ("today UTC", message="La date de début doit être supérieure à la date du jour !")
     * @Assert\GreaterThan (value="10", message="Une sortie doit être supérieure à 10 minutes !")
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duree;

    /**
     * @Assert\Type("DateTimeInterface")
     * @Assert\NotBlank
     * @ORM\Column(type="datetime")
     */
    private $dateLimiteInscription;

    /**
     * @Assert\NotBlank
     * @Assert\GreaterThan (value="1", message="Le nombre de participant doit être au moins de 2 !")
     * @ORM\Column(type="integer")
     */
    private $nbInscriptionMax;

    /**
     * @Assert\NotBlank (message="Veuillez remplir une description de la sortie (min 30 caractères)!")
     * @Assert\Length (min=30,
     *     max=3000,
     *      minMessage="Veuillez remplir une description de la sortie (min 30 caractères)!",
     *     maxMessage="Le nombre de caractères maximal est de 3000 !")
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $infosSortie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sortiesOrganisateur")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateur;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="sortiesUser")
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity=Lieu::class, inversedBy="sortie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lieu;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $raisonAnnulation;

    public function __construct()
    {
        $this->user = new ArrayCollection();
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

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbInscriptionMax(): ?int
    {
        return $this->nbInscriptionMax;
    }

    public function setNbInscriptionMax(int $nbInscriptionMax): self
    {
        $this->nbInscriptionMax = $nbInscriptionMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(?string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }


    public function getOrganisateur(): ?User
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?User $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    /**
     * @return Collection|user[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $user): self
    {
        if (!$this->participants->contains($user)) {
            $this->participants[] = $user;
        }

        return $this;
    }


    public function removeUser(User $user): self
    {
        $this->participants->removeElement($user);

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getRaisonAnnulation(): ?string
    {
        return $this->raisonAnnulation;
    }

    public function setRaisonAnnulation(?string $raisonAnnulation): self
    {
        $this->raisonAnnulation = $raisonAnnulation;

        return $this;
    }
}