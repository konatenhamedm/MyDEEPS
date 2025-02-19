<?php

namespace App\Entity;

use App\Repository\TempEtablissementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups as Group;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TempEtablissementRepository::class)]
class TempEtablissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(type: 'string',  nullable: true)]
    #[Group(["group1", "group_user", 'group_pro'])]
    private ?string $username = null;

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\Email]
    #[Group(["group1", "group_user", 'group_pro'])]
    private ?string $email = null;

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomEntite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numero = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $annee = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column( nullable: true)]
    #[Group(['group_pro'])]
    private ?string $appartenirOrganisation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $genre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $reason = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $status = null;


    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $typePersonne = null;



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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeUser = null;

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get the value of appartenirOrganisation
     */ 
    public function getAppartenirOrganisation()
    {
        return $this->appartenirOrganisation;
    }

    /**
     * Set the value of appartenirOrganisation
     *
     * @return  self
     */ 
    public function setAppartenirOrganisation($appartenirOrganisation)
    {
        $this->appartenirOrganisation = $appartenirOrganisation;

        return $this;
    }

    /**
     * Get the value of genre
     */ 
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set the value of genre
     *
     * @return  self
     */ 
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get the value of reason
     */ 
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set the value of reason
     *
     * @return  self
     */ 
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of typePersonne
     */ 
    public function getTypePersonne()
    {
        return $this->typePersonne;
    }

    /**
     * Set the value of typePersonne
     *
     * @return  self
     */ 
    public function setTypePersonne($typePersonne)
    {
        $this->typePersonne = $typePersonne;

        return $this;
    }

    /**
     * Get the value of photo
     */ 
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set the value of photo
     *
     * @return  self
     */ 
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get the value of cni
     */ 
    public function getCni()
    {
        return $this->cni;
    }

    /**
     * Set the value of cni
     *
     * @return  self
     */ 
    public function setCni($cni)
    {
        $this->cni = $cni;

        return $this;
    }

    /**
     * Get the value of diplomeFile
     */ 
    public function getDiplomeFile()
    {
        return $this->diplomeFile;
    }

    /**
     * Set the value of diplomeFile
     *
     * @return  self
     */ 
    public function setDiplomeFile($diplomeFile)
    {
        $this->diplomeFile = $diplomeFile;

        return $this;
    }

    /**
     * Get the value of cv
     */ 
    public function getCv()
    {
        return $this->cv;
    }

    /**
     * Set the value of cv
     *
     * @return  self
     */ 
    public function setCv($cv)
    {
        $this->cv = $cv;

        return $this;
    }

    /**
     * Get the value of dfe
     */ 
    public function getDfe()
    {
        return $this->dfe;
    }

    /**
     * Set the value of dfe
     *
     * @return  self
     */ 
    public function setDfe($dfe)
    {
        $this->dfe = $dfe;

        return $this;
    }

    /**
     * Get the value of ordreNational
     */ 
    public function getOrdreNational()
    {
        return $this->ordreNational;
    }

    /**
     * Set the value of ordreNational
     *
     * @return  self
     */ 
    public function setOrdreNational($ordreNational)
    {
        $this->ordreNational = $ordreNational;

        return $this;
    }

    /**
     * Get the value of nomEntreprise
     */ 
    public function getNomEntreprise()
    {
        return $this->nomEntreprise;
    }

    /**
     * Set the value of nomEntreprise
     *
     * @return  self
     */ 
    public function setNomEntreprise($nomEntreprise)
    {
        $this->nomEntreprise = $nomEntreprise;

        return $this;
    }

    /**
     * Get the value of natureEntreprise
     */ 
    public function getNatureEntreprise()
    {
        return $this->natureEntreprise;
    }

    /**
     * Set the value of natureEntreprise
     *
     * @return  self
     */ 
    public function setNatureEntreprise($natureEntreprise)
    {
        $this->natureEntreprise = $natureEntreprise;

        return $this;
    }

    /**
     * Get the value of typeEntreprise
     */ 
    public function getTypeEntreprise()
    {
        return $this->typeEntreprise;
    }

    /**
     * Set the value of typeEntreprise
     *
     * @return  self
     */ 
    public function setTypeEntreprise($typeEntreprise)
    {
        $this->typeEntreprise = $typeEntreprise;

        return $this;
    }

    /**
     * Get the value of gpsEntreprise
     */ 
    public function getGpsEntreprise()
    {
        return $this->gpsEntreprise;
    }

    /**
     * Set the value of gpsEntreprise
     *
     * @return  self
     */ 
    public function setGpsEntreprise($gpsEntreprise)
    {
        $this->gpsEntreprise = $gpsEntreprise;

        return $this;
    }

    /**
     * Get the value of niveauEntreprise
     */ 
    public function getNiveauEntreprise()
    {
        return $this->niveauEntreprise;
    }

    /**
     * Set the value of niveauEntreprise
     *
     * @return  self
     */ 
    public function setNiveauEntreprise($niveauEntreprise)
    {
        $this->niveauEntreprise = $niveauEntreprise;

        return $this;
    }

    /**
     * Get the value of contactEntreprise
     */ 
    public function getContactEntreprise()
    {
        return $this->contactEntreprise;
    }

    /**
     * Set the value of contactEntreprise
     *
     * @return  self
     */ 
    public function setContactEntreprise($contactEntreprise)
    {
        $this->contactEntreprise = $contactEntreprise;

        return $this;
    }

    /**
     * Get the value of emailEntreprise
     */ 
    public function getEmailEntreprise()
    {
        return $this->emailEntreprise;
    }

    /**
     * Set the value of emailEntreprise
     *
     * @return  self
     */ 
    public function setEmailEntreprise($emailEntreprise)
    {
        $this->emailEntreprise = $emailEntreprise;

        return $this;
    }

    /**
     * Get the value of spaceEntreprise
     */ 
    public function getSpaceEntreprise()
    {
        return $this->spaceEntreprise;
    }

    /**
     * Set the value of spaceEntreprise
     *
     * @return  self
     */ 
    public function setSpaceEntreprise($spaceEntreprise)
    {
        $this->spaceEntreprise = $spaceEntreprise;

        return $this;
    }

    /**
     * Get the value of nomCompletPromoteur
     */ 
    public function getNomCompletPromoteur()
    {
        return $this->nomCompletPromoteur;
    }

    /**
     * Set the value of nomCompletPromoteur
     *
     * @return  self
     */ 
    public function setNomCompletPromoteur($nomCompletPromoteur)
    {
        $this->nomCompletPromoteur = $nomCompletPromoteur;

        return $this;
    }

    /**
     * Get the value of emailPro
     */ 
    public function getEmailPro()
    {
        return $this->emailPro;
    }

    /**
     * Set the value of emailPro
     *
     * @return  self
     */ 
    public function setEmailPro($emailPro)
    {
        $this->emailPro = $emailPro;

        return $this;
    }

    /**
     * Get the value of profession
     */ 
    public function getProfession()
    {
        return $this->profession;
    }

    /**
     * Set the value of profession
     *
     * @return  self
     */ 
    public function setProfession($profession)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get the value of contactsPromoteur
     */ 
    public function getContactsPromoteur()
    {
        return $this->contactsPromoteur;
    }

    /**
     * Set the value of contactsPromoteur
     *
     * @return  self
     */ 
    public function setContactsPromoteur($contactsPromoteur)
    {
        $this->contactsPromoteur = $contactsPromoteur;

        return $this;
    }

    /**
     * Get the value of lieuResidence
     */ 
    public function getLieuResidence()
    {
        return $this->lieuResidence;
    }

    /**
     * Set the value of lieuResidence
     *
     * @return  self
     */ 
    public function setLieuResidence($lieuResidence)
    {
        $this->lieuResidence = $lieuResidence;

        return $this;
    }

    /**
     * Get the value of numeroCni
     */ 
    public function getNumeroCni()
    {
        return $this->numeroCni;
    }

    /**
     * Set the value of numeroCni
     *
     * @return  self
     */ 
    public function setNumeroCni($numeroCni)
    {
        $this->numeroCni = $numeroCni;

        return $this;
    }

    /**
     * Get the value of nomCompletTechnique
     */ 
    public function getNomCompletTechnique()
    {
        return $this->nomCompletTechnique;
    }

    /**
     * Set the value of nomCompletTechnique
     *
     * @return  self
     */ 
    public function setNomCompletTechnique($nomCompletTechnique)
    {
        $this->nomCompletTechnique = $nomCompletTechnique;

        return $this;
    }

    /**
     * Get the value of emailProTechnique
     */ 
    public function getEmailProTechnique()
    {
        return $this->emailProTechnique;
    }

    /**
     * Set the value of emailProTechnique
     *
     * @return  self
     */ 
    public function setEmailProTechnique($emailProTechnique)
    {
        $this->emailProTechnique = $emailProTechnique;

        return $this;
    }

    /**
     * Get the value of professionTechnique
     */ 
    public function getProfessionTechnique()
    {
        return $this->professionTechnique;
    }

    /**
     * Set the value of professionTechnique
     *
     * @return  self
     */ 
    public function setProfessionTechnique($professionTechnique)
    {
        $this->professionTechnique = $professionTechnique;

        return $this;
    }

    /**
     * Get the value of contactProTechnique
     */ 
    public function getContactProTechnique()
    {
        return $this->contactProTechnique;
    }

    /**
     * Set the value of contactProTechnique
     *
     * @return  self
     */ 
    public function setContactProTechnique($contactProTechnique)
    {
        $this->contactProTechnique = $contactProTechnique;

        return $this;
    }

    /**
     * Get the value of lieuResidenceTechnique
     */ 
    public function getLieuResidenceTechnique()
    {
        return $this->lieuResidenceTechnique;
    }

    /**
     * Set the value of lieuResidenceTechnique
     *
     * @return  self
     */ 
    public function setLieuResidenceTechnique($lieuResidenceTechnique)
    {
        $this->lieuResidenceTechnique = $lieuResidenceTechnique;

        return $this;
    }

    /**
     * Get the value of numeroOrdreTechnique
     */ 
    public function getNumeroOrdreTechnique()
    {
        return $this->numeroOrdreTechnique;
    }

    /**
     * Set the value of numeroOrdreTechnique
     *
     * @return  self
     */ 
    public function setNumeroOrdreTechnique($numeroOrdreTechnique)
    {
        $this->numeroOrdreTechnique = $numeroOrdreTechnique;

        return $this;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of nomEntite
     */ 
    public function getNomEntite()
    {
        return $this->nomEntite;
    }

    /**
     * Set the value of nomEntite
     *
     * @return  self
     */ 
    public function setNomEntite($nomEntite)
    {
        $this->nomEntite = $nomEntite;

        return $this;
    }

    /**
     * Get the value of numero
     */ 
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set the value of numero
     *
     * @return  self
     */ 
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get the value of annee
     */ 
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set the value of annee
     *
     * @return  self
     */ 
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    public function getTypeUser(): ?string
    {
        return $this->typeUser;
    }

    public function setTypeUser(?string $typeUser): static
    {
        $this->typeUser = $typeUser;

        return $this;
    }
}
