<?php

namespace App\Entity;

use App\Repository\RechercheRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RechercheRepository::class)
 */
class Recherche
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Site::class)
     */
    private $site;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateMin;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateMax;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $organisateur;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $inscrit;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $pasInscrit;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $passees;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateMin(): ?\DateTimeInterface
    {
        return $this->dateMin;
    }

    public function setDateMin(?\DateTimeInterface $dateMin): self
    {
        $this->dateMin = $dateMin;

        return $this;
    }

    public function getDateMax(): ?\DateTimeInterface
    {
        return $this->dateMax;
    }

    public function setDateMax(?\DateTimeInterface $dateMax): self
    {
        $this->dateMax = $dateMax;

        return $this;
    }

    public function getOrganisateur(): ?bool
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?bool $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getInscrit(): ?bool
    {
        return $this->inscrit;
    }

    public function setInscrit(?bool $inscrit): self
    {
        $this->inscrit = $inscrit;

        return $this;
    }

    public function getPasInscrit(): ?bool
    {
        return $this->pasInscrit;
    }

    public function setPasInscrit(?bool $pasInscrit): self
    {
        $this->pasInscrit = $pasInscrit;

        return $this;
    }

    public function getPassees(): ?bool
    {
        return $this->passees;
    }

    public function setPassees(?bool $passees): self
    {
        $this->passees = $passees;

        return $this;
    }
}
