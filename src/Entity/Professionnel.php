<?php

namespace App\Entity;

use App\Repository\ProfessionnelRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups as Group;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProfessionnelRepository::class)]
#[Table(name: 'membre_professionnel')]
class Professionnel extends Entite
{
    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro","group_user_trx"])]
    private ?string $number = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro","group_user_trx"])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro","group_user_trx"])]
    private ?string $prenoms = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $emailPro = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $professionnel = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $addressPro = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $profession = null;

    #[ORM\ManyToOne(inversedBy: 'professionnels')]
    #[Group(["group_pro"])]
    private ?Civilite $civilite = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Group(["group_pro"])]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\ManyToOne(inversedBy: 'professionnels')]
    private ?Pays $nationate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $lieuResidence = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $adresseEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $contactPro = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $situation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $diplome = null;

   

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Group(["group_pro"])]
    private ?\DateTimeInterface $dateDiplome = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Group(["group_pro"])]
    private ?\DateTimeInterface $dateEmploi = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $situationPro = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $photo = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $diplomeFile = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $cni = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $cv = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $casier = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["fichier","group_pro"])]
    private ?Fichier $certificat = null;

    #[ORM\ManyToOne(inversedBy: 'professionnels')]
    #[Group(["group_pro"])]
    private ?Specialite $specialite = null;


    #[ORM\ManyToOne(inversedBy: 'professionnels')]
    #[Group(["group_pro"])]
    private ?Ville $ville = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $lieuDiplome = null;


    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber($number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getProfessionnel(): ?string
    {
        return $this->professionnel;
    }

    public function setProfessionnel(?string $professionnel): static
    {
        $this->professionnel = $professionnel;

        return $this;
    }

    public function getAddressPro(): ?string
    {
        return $this->addressPro;
    }

    public function setAddressPro(?string $addressPro): static
    {
        $this->addressPro = $addressPro;

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

    public function getCivilite(): ?Civilite
    {
        return $this->civilite;
    }

    public function setCivilite(?Civilite $civilite): static
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getNationate(): ?Pays
    {
        return $this->nationate;
    }

    public function setNationate(?Pays $nationate): static
    {
        $this->nationate = $nationate;

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

    public function getAdresseEmail(): ?string
    {
        return $this->adresseEmail;
    }

    public function setAdresseEmail(?string $adresseEmail): static
    {
        $this->adresseEmail = $adresseEmail;

        return $this;
    }

    public function getContactPro(): ?string
    {
        return $this->contactPro;
    }

    public function setContactPro(string $contactPro): static
    {
        $this->contactPro = $contactPro;

        return $this;
    }

    public function getSituation(): ?string
    {
        return $this->situation;
    }

    public function setSituation(string $situation): static
    {
        $this->situation = $situation;

        return $this;
    }

    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(string $diplome): static
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getDateDiplome(): ?\DateTimeInterface
    {
        return $this->dateDiplome;
    }

    public function setDateDiplome(?\DateTimeInterface $dateDiplome): static
    {
        $this->dateDiplome = $dateDiplome;

        return $this;
    }

    public function getSituationPro(): ?string
    {
        return $this->situationPro;
    }

    public function setSituationPro(string $situationPro): static
    {
        $this->situationPro = $situationPro;

        return $this;
    }

    public function getPhoto(): ?Fichier
    {
        return $this->photo;
    }

    public function setPhoto(?Fichier $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getDiplomeFile(): ?Fichier
    {
        return $this->diplomeFile;
    }

    public function setDiplomeFile(?Fichier $diplomeFile): static
    {
        $this->diplomeFile = $diplomeFile;

        return $this;
    }

    public function getCni(): ?Fichier
    {
        return $this->cni;
    }

    public function setCni(?Fichier $cni): static
    {
        $this->cni = $cni;

        return $this;
    }

    public function getCv(): ?Fichier
    {
        return $this->cv;
    }

    public function setCv(?Fichier $cv): static
    {
        $this->cv = $cv;

        return $this;
    }

    public function getCasier(): ?Fichier
    {
        return $this->casier;
    }

    public function setCasier(?Fichier $casier): static
    {
        $this->casier = $casier;

        return $this;
    }

    public function getCertificat(): ?Fichier
    {
        return $this->certificat;
    }

    public function setCertificat(?Fichier $certificat): static
    {
        $this->certificat = $certificat;

        return $this;
    }

    /**
     * Get the value of prenoms
     */ 
    public function getPrenoms()
    {
        return $this->prenoms;
    }

    /**
     * Set the value of prenoms
     *
     * @return  self
     */ 
    public function setPrenoms($prenoms)
    {
        $this->prenoms = $prenoms;

        return $this;
    }
    public function getDateEmploi(): ?\DateTimeInterface
    {
        return $this->dateEmploi;
    }

    public function setDateEmploi(?\DateTimeInterface $dateEmploi): static
    {
        $this->dateEmploi = $dateEmploi;

        return $this;
    }

    public function getSpecialite(): ?Specialite
    {
        return $this->specialite;
    }

    public function setSpecialite(?Specialite $specialite): static
    {
        $this->specialite = $specialite;

        return $this;
    }

  

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getLieuDiplome(): ?string
    {
        return $this->lieuDiplome;
    }

    public function setLieuDiplome(string $lieuDiplome): static
    {
        $this->lieuDiplome = $lieuDiplome;

        return $this;
    }

}
