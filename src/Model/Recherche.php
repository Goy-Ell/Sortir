<?php

namespace App\Model;

use App\Entity\Site;
use App\Entity\User;
use App\Repository\SortieRepository;
use Doctrine\ORM\Mapping as ORM;


class Recherche
{

    private $id;

    private $user;

    private $site;


    private $nom;


    private $dateMin;


    private $dateMax;


    private $organisateur;


    private $inscrit;


    private $pasInscrit;


    private $passees;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
