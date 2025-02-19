<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use Doctrine\DBAL\Types\Types;
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



    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $photo = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $cni = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $diplomeFile = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $cv = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $dfe = null;

  #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $ordreNational = null;



    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?string $nomEntreprise = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?string $natureEntreprise = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?string $typeEntreprise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gpsEntreprise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $niveauEntreprise = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?string $contactEntreprise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailEntreprise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $spaceEntreprise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomCompletPromoteur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailPro = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profession = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactsPromoteur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieuResidence = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroCni = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?string $nomCompletTechnique = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailProTechnique = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $professionTechnique = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactProTechnique = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieuResidenceTechnique = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroOrdreTechnique = null;

   




    public function getTypePersonne(): ?TypePersonne
    {
        return $this->typePersonne;
    }

    public function setTypePersonne(?TypePersonne $typePersonne): static
    {
        $this->typePersonne = $typePersonne;

        return $this;
    }


    /**
     * Get the value of photo
     */ 
    public function getPhoto(): ?Fichier
    {
        return $this->photo;
    }

    /**
     * Set the value of photo
     *
     * @return  self
     */ 
    public function setPhoto(?Fichier $photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get the value of cni
     */ 
    public function getCni(): ?Fichier
    {
        return $this->cni;
    }

    /**
     * Set the value of cni
     *
     * @return  self
     */ 
    public function setCni(?Fichier $cni)
    {
        $this->cni = $cni;

        return $this;
    }

    /**
     * Get the value of dfe
     */ 
    public function getDfe(): ?Fichier
    {
        return $this->dfe;
    }

    /**
     * Set the value of dfe
     *
     * @return  self
     */ 
    public function setDfe(?Fichier $dfe)
    {
        $this->dfe = $dfe;

        return $this;
    }
      /**
     * Get the value of ordreNational
     */ 
    public function getOrdreNational(): ?Fichier
    {
        return $this->ordreNational;
    }

    /**
     * Set the value of ordreNational
     *
     * @return  self
     */ 
    public function setOrdreNational(?Fichier $ordreNational)
    {
        $this->ordreNational = $ordreNational;

        return $this;
    }

    /**
     * Get the value of diplomeFile
     */ 
    public function getDiplomeFile() :?Fichier
    {
        return $this->diplomeFile;
    }

    /**
     * Set the value of diplomeFile
     *
     * @return  self
     */ 
    public function setDiplomeFile(?Fichier $diplomeFile)
    {
        $this->diplomeFile = $diplomeFile;

        return $this;
    }

    /**
     * Get the value of cv
     */ 
    public function getCv():?Fichier
    {
        return $this->cv;
    }

    /**
     * Set the value of cv
     *
     * @return  self
     */ 
    public function setCv(?Fichier $cv)
    {
        $this->cv = $cv;

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

    public function getTypeEntreprise(): ?string
    {
        return $this->typeEntreprise;
    }

    public function setTypeEntreprise(?string $typeEntreprise): static
    {
        $this->typeEntreprise = $typeEntreprise;

        return $this;
    }

    public function getGpsEntreprise(): ?string
    {
        return $this->gpsEntreprise;
    }

    public function setGpsEntreprise(?string $gpsEntreprise): static
    {
        $this->gpsEntreprise = $gpsEntreprise;

        return $this;
    }

    public function getNiveauEntreprise(): ?string
    {
        return $this->niveauEntreprise;
    }

    public function setNiveauEntreprise(?string $niveauEntreprise): static
    {
        $this->niveauEntreprise = $niveauEntreprise;

        return $this;
    }

    public function getContactEntreprise(): ?string
    {
        return $this->contactEntreprise;
    }

    public function setContactEntreprise(?string $contactEntreprise): static
    {
        $this->contactEntreprise = $contactEntreprise;

        return $this;
    }

    public function getEmailEntreprise(): ?string
    {
        return $this->emailEntreprise;
    }

    public function setEmailEntreprise(?string $emailEntreprise): static
    {
        $this->emailEntreprise = $emailEntreprise;

        return $this;
    }

    public function getSpaceEntreprise(): ?string
    {
        return $this->spaceEntreprise;
    }

    public function setSpaceEntreprise(?string $spaceEntreprise): static
    {
        $this->spaceEntreprise = $spaceEntreprise;

        return $this;
    }

    public function getNomCompletPromoteur(): ?string
    {
        return $this->nomCompletPromoteur;
    }

    public function setNomCompletPromoteur(?string $nomCompletPromoteur): static
    {
        $this->nomCompletPromoteur = $nomCompletPromoteur;

        return $this;
    }

    public function getEmailPro(): ?string
    {
        return $this->emailPro;
    }

    public function setEmailPro(?string $emailPro): static
    {
        $this->emailPro = $emailPro;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): static
    {
        $this->profession = $profession;

        return $this;
    }

    public function getContactsPromoteur(): ?string
    {
        return $this->contactsPromoteur;
    }

    public function setContactsPromoteur(?string $contactsPromoteur): static
    {
        $this->contactsPromoteur = $contactsPromoteur;

        return $this;
    }

    public function getLieuResidence(): ?string
    {
        return $this->lieuResidence;
    }

    public function setLieuResidence(?string $lieuResidence): static
    {
        $this->lieuResidence = $lieuResidence;

        return $this;
    }

    public function getNumeroCni(): ?string
    {
        return $this->numeroCni;
    }

    public function setNumeroCni(?string $numeroCni): static
    {
        $this->numeroCni = $numeroCni;

        return $this;
    }

    public function getNomCompletTechnique(): ?string
    {
        return $this->nomCompletTechnique;
    }

    public function setNomCompletTechnique(?string $nomCompletTechnique): static
    {
        $this->nomCompletTechnique = $nomCompletTechnique;

        return $this;
    }

    public function getEmailProTechnique(): ?string
    {
        return $this->emailProTechnique;
    }

    public function setEmailProTechnique(?string $emailProTechnique): static
    {
        $this->emailProTechnique = $emailProTechnique;

        return $this;
    }

    public function getProfessionTechnique(): ?string
    {
        return $this->professionTechnique;
    }

    public function setProfessionTechnique(?string $professionTechnique): static
    {
        $this->professionTechnique = $professionTechnique;

        return $this;
    }

    public function getContactProTechnique(): ?string
    {
        return $this->contactProTechnique;
    }

    public function setContactProTechnique(?string $contactProTechnique): static
    {
        $this->contactProTechnique = $contactProTechnique;

        return $this;
    }

    public function getLieuResidenceTechnique(): ?string
    {
        return $this->lieuResidenceTechnique;
    }

    public function setLieuResidenceTechnique(?string $lieuResidenceTechnique): static
    {
        $this->lieuResidenceTechnique = $lieuResidenceTechnique;

        return $this;
    }

    public function getNumeroOrdreTechnique(): ?string
    {
        return $this->numeroOrdreTechnique;
    }

    public function setNumeroOrdreTechnique(?string $numeroOrdreTechnique): static
    {
        $this->numeroOrdreTechnique = $numeroOrdreTechnique;

        return $this;
    }

    

   
}
