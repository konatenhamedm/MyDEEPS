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
    #[ORM\Column(length: 255)]
    private ?string $number = null;

    #[ORM\Column(length: 255)]
    private ?string $fullName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailPro = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $professionnel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $addressPro = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profession = null;

    #[ORM\ManyToOne(inversedBy: 'professionnels')]
    private ?Civilite $civilite = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\ManyToOne(inversedBy: 'professionnels')]
    private ?Pays $nationate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieuResidence = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresseEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactPro = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $situation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $diplome = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dateDiplome = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $situationPro = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Fichier $photo = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Fichier $diplomeFile = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Fichier $cni = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Fichier $cv = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Fichier $casier = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Fichier $certificat = null;

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): static
    {
        $this->fullName = $fullName;

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

    public function getDateDiplome(): ?string
    {
        return $this->dateDiplome;
    }

    public function setDateDiplome(string $dateDiplome): static
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
}
