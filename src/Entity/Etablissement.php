<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups as Group;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EtablissementRepository::class)]
#[Table(name: 'membre_etablissement')]
class Etablissement extends Entite
{
    #[ORM\ManyToOne(inversedBy: 'etablissements')]
    #[Group(["group_pro"])]
    private ?TypePersonne $typePersonne = null;

    #[ORM\ManyToOne(inversedBy: 'etablissements')]
    #[Group(["group_pro"])]
    private ?Genre $genre = null;


    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $photoPhysique = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $cniPhysique = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $diplomeFilePhysique = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $cvPhysique = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $dfePhysique = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomEntreprise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $natureEntreprise = null;





    public function getTypePersonne(): ?TypePersonne
    {
        return $this->typePersonne;
    }

    public function setTypePersonne(?TypePersonne $typePersonne): static
    {
        $this->typePersonne = $typePersonne;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): static
    {
        $this->genre = $genre;

        return $this;
    }




    /**
     * Get the value of photoPhysique
     */ 
    public function getPhotoPhysique(): ?Fichier
    {
        return $this->photoPhysique;
    }

    /**
     * Set the value of photoPhysique
     *
     * @return  self
     */ 
    public function setPhotoPhysique(?Fichier $photoPhysique)
    {
        $this->photoPhysique = $photoPhysique;

        return $this;
    }

    /**
     * Get the value of cniPhysique
     */ 
    public function getCniPhysique(): ?Fichier
    {
        return $this->cniPhysique;
    }

    /**
     * Set the value of cniPhysique
     *
     * @return  self
     */ 
    public function setCniPhysique(?Fichier $cniPhysique)
    {
        $this->cniPhysique = $cniPhysique;

        return $this;
    }

    /**
     * Get the value of dfePhysique
     */ 
    public function getDfePhysique(): ?Fichier
    {
        return $this->dfePhysique;
    }

    /**
     * Set the value of dfePhysique
     *
     * @return  self
     */ 
    public function setDfePhysique(?Fichier $dfePhysique)
    {
        $this->dfePhysique = $dfePhysique;

        return $this;
    }

    /**
     * Get the value of diplomeFilePhysique
     */ 
    public function getDiplomeFilePhysique() :?Fichier
    {
        return $this->diplomeFilePhysique;
    }

    /**
     * Set the value of diplomeFilePhysique
     *
     * @return  self
     */ 
    public function setDiplomeFilePhysique(?Fichier $diplomeFilePhysique)
    {
        $this->diplomeFilePhysique = $diplomeFilePhysique;

        return $this;
    }

    /**
     * Get the value of cvPhysique
     */ 
    public function getCvPhysique():?Fichier
    {
        return $this->cvPhysique;
    }

    /**
     * Set the value of cvPhysique
     *
     * @return  self
     */ 
    public function setCvPhysique(?Fichier $cvPhysique)
    {
        $this->cvPhysique = $cvPhysique;

        return $this;
    }

    public function getNomEntreprise(): ?string
    {
        return $this->nomEntreprise;
    }

    public function setNomEntreprise(?string $nomEntreprise): static
    {
        $this->nomEntreprise = $nomEntreprise;

        return $this;
    }

    public function getNatureEntreprise(): ?string
    {
        return $this->natureEntreprise;
    }

    public function setNatureEntreprise(?string $natureEntreprise): static
    {
        $this->natureEntreprise = $natureEntreprise;

        return $this;
    }
}
