<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups as Group;


#[ORM\Entity(repositoryClass: EtablissementRepository::class)]
#[Table(name: 'membre_etablissement')]
class Etablissement extends Entite
{
    #[ORM\ManyToOne(inversedBy: 'etablissements')]
    private ?TypePersonne $typePersonne = null;

    #[ORM\ManyToOne(inversedBy: 'etablissements')]
    private ?Genre $genre = null;


    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $entrepriseName = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $contact = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $niveau = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $gps = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $nature = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $emailEntreprise = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $space = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $nomComplet = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $emailPro = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $prefession = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $contactPro = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $lieuResidence = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $numeroCni = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $nomCompletTechnique = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $emailProTechnique = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $prefessionTechnique = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $contactProTechnique = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $lieuResidenceTechnique = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $numeroOrdreTechnique = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $rs = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $objet = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $ss = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $numeroRc = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $representant = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $contacts = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $acteCreation = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $declaration = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $reglementInterieur = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $dfe = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $rc = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $acd = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $plan = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $topographique = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $facture = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $situation = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $certificat = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $attestation = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $attestationInscription = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $photoRespo = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $decision = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $certificatRespo = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $extraitRespo = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $visiteMedicale = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Fichier $inscriptionProfession = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Fichier $photoPhysique = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Fichier $cniPhysique = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Fichier $diplomeFilePhysique = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Fichier $cvPhysique = null;

    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Fichier $dfePhysique = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $professionTechnique = null;

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


    public function getEntrepriseName(): ?string
    {
        return $this->entrepriseName;
    }
    public function setEntrepriseName(?string $entrepriseName): self
    {
        $this->entrepriseName = $entrepriseName;
        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }
    public function setContact(?string $contact): self
    {
        $this->contact = $contact;
        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }
    public function setNiveau(?string $niveau): self
    {
        $this->niveau = $niveau;
        return $this;
    }

    public function getGps(): ?string
    {
        return $this->gps;
    }
    public function setGps(?string $gps): self
    {
        $this->gps = $gps;
        return $this;
    }

    public function getNature(): ?string
    {
        return $this->nature;
    }
    public function setNature(?string $nature): self
    {
        $this->nature = $nature;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }
    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getEmailEntreprise(): ?string
    {
        return $this->emailEntreprise;
    }
    public function setEmailEntreprise(?string $emailEntreprise): self
    {
        $this->emailEntreprise = $emailEntreprise;
        return $this;
    }

    public function getSpace(): ?string
    {
        return $this->space;
    }
    public function setSpace(?string $space): self
    {
        $this->space = $space;
        return $this;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }
    public function setNomComplet(?string $nomComplet): self
    {
        $this->nomComplet = $nomComplet;
        return $this;
    }

    public function getEmailPro(): ?string
    {
        return $this->emailPro;
    }
    public function setEmailPro(?string $emailPro): self
    {
        $this->emailPro = $emailPro;
        return $this;
    }


    public function getContactPro(): ?string
    {
        return $this->contactPro;
    }
    public function setContactPro(?string $contactPro): self
    {
        $this->contactPro = $contactPro;
        return $this;
    }

    public function getLieuResidence(): ?string
    {
        return $this->lieuResidence;
    }
    public function setLieuResidence(?string $lieuResidence): self
    {
        $this->lieuResidence = $lieuResidence;
        return $this;
    }

    public function getNumeroCni(): ?string
    {
        return $this->numeroCni;
    }
    public function setNumeroCni(?string $numeroCni): self
    {
        $this->numeroCni = $numeroCni;
        return $this;
    }

    public function getNomCompletTechnique(): ?string
    {
        return $this->nomCompletTechnique;
    }
    public function setNomCompletTechnique(?string $nomCompletTechnique): self
    {
        $this->nomCompletTechnique = $nomCompletTechnique;
        return $this;
    }

    public function getEmailProTechnique(): ?string
    {
        return $this->emailProTechnique;
    }
    public function setEmailProTechnique(?string $emailProTechnique): self
    {
        $this->emailProTechnique = $emailProTechnique;
        return $this;
    }

    public function getProfessionTechnique(): ?string
    {
        return $this->professionTechnique;
    }
    public function setProfessionTechnique(?string $professionTechnique): self
    {
        $this->professionTechnique = $professionTechnique;
        return $this;
    }

    public function getContactProTechnique(): ?string
    {
        return $this->contactProTechnique;
    }
    public function setContactProTechnique(?string $contactProTechnique): self
    {
        $this->contactProTechnique = $contactProTechnique;
        return $this;
    }

    public function getLieuResidenceTechnique(): ?string
    {
        return $this->lieuResidenceTechnique;
    }
    public function setLieuResidenceTechnique(?string $lieuResidenceTechnique): self
    {
        $this->lieuResidenceTechnique = $lieuResidenceTechnique;
        return $this;
    }

    public function getNumeroOrdreTechnique(): ?string
    {
        return $this->numeroOrdreTechnique;
    }
    public function setNumeroOrdreTechnique(?string $numeroOrdreTechnique): self
    {
        $this->numeroOrdreTechnique = $numeroOrdreTechnique;
        return $this;
    }

    public function getInscriptionProfession(): ?string
    {
        return $this->inscriptionProfession;
    }

    public function setInscriptionProfession(string $inscriptionProfession): static
    {
        $this->inscriptionProfession = $inscriptionProfession;

        return $this;
    }

    /**
     * Get the value of prefession
     */ 
    public function getPrefession()
    {
        return $this->prefession;
    }

    /**
     * Set the value of prefession
     *
     * @return  self
     */ 
    public function setPrefession($prefession)
    {
        $this->prefession = $prefession;

        return $this;
    }

    /**
     * Get the value of rs
     */ 
    public function getRs()
    {
        return $this->rs;
    }

    /**
     * Set the value of rs
     *
     * @return  self
     */ 
    public function setRs($rs)
    {
        $this->rs = $rs;

        return $this;
    }

    /**
     * Get the value of visiteMedicale
     */ 
    public function getVisiteMedicale()
    {
        return $this->visiteMedicale;
    }

    /**
     * Set the value of visiteMedicale
     *
     * @return  self
     */ 
    public function setVisiteMedicale($visiteMedicale)
    {
        $this->visiteMedicale = $visiteMedicale;

        return $this;
    }

    /**
     * Get the value of extraitRespo
     */ 
    public function getExtraitRespo()
    {
        return $this->extraitRespo;
    }

    /**
     * Set the value of extraitRespo
     *
     * @return  self
     */ 
    public function setExtraitRespo($extraitRespo)
    {
        $this->extraitRespo = $extraitRespo;

        return $this;
    }

    /**
     * Get the value of certificatRespo
     */ 
    public function getCertificatRespo()
    {
        return $this->certificatRespo;
    }

    /**
     * Set the value of certificatRespo
     *
     * @return  self
     */ 
    public function setCertificatRespo($certificatRespo)
    {
        $this->certificatRespo = $certificatRespo;

        return $this;
    }

    /**
     * Get the value of decision
     */ 
    public function getDecision()
    {
        return $this->decision;
    }

    /**
     * Set the value of decision
     *
     * @return  self
     */ 
    public function setDecision($decision)
    {
        $this->decision = $decision;

        return $this;
    }

    /**
     * Get the value of objet
     */ 
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set the value of objet
     *
     * @return  self
     */ 
    public function setObjet($objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get the value of prefessionTechnique
     */ 
    public function getPrefessionTechnique()
    {
        return $this->prefessionTechnique;
    }

    /**
     * Set the value of prefessionTechnique
     *
     * @return  self
     */ 
    public function setPrefessionTechnique($prefessionTechnique)
    {
        $this->prefessionTechnique = $prefessionTechnique;

        return $this;
    }

    /**
     * Get the value of ss
     */ 
    public function getSs()
    {
        return $this->ss;
    }

    /**
     * Set the value of ss
     *
     * @return  self
     */ 
    public function setSs($ss)
    {
        $this->ss = $ss;

        return $this;
    }

    /**
     * Get the value of numeroRc
     */ 
    public function getNumeroRc()
    {
        return $this->numeroRc;
    }

    /**
     * Set the value of numeroRc
     *
     * @return  self
     */ 
    public function setNumeroRc($numeroRc)
    {
        $this->numeroRc = $numeroRc;

        return $this;
    }

    public function getRepresentant(): ?string
    {
        return $this->representant;
    }

    public function setRepresentant(?string $representant): self
    {
        $this->representant = $representant;
        return $this;
    }

    public function getContacts(): ?string
    {
        return $this->contacts;
    }

    public function setContacts(?string $contacts): self
    {
        $this->contacts = $contacts;
        return $this;
    }

    public function getActeCreation(): ?string
    {
        return $this->acteCreation;
    }

    public function setActeCreation(?string $acteCreation): self
    {
        $this->acteCreation = $acteCreation;
        return $this;
    }

    public function getDeclaration(): ?string
    {
        return $this->declaration;
    }

    public function setDeclaration(?string $declaration): self
    {
        $this->declaration = $declaration;
        return $this;
    }

    public function getReglementInterieur(): ?string
    {
        return $this->reglementInterieur;
    }

    public function setReglementInterieur(?string $reglementInterieur): self
    {
        $this->reglementInterieur = $reglementInterieur;
        return $this;
    }

    public function getDfe(): ?string
    {
        return $this->dfe;
    }

    public function setDfe(?string $dfe): self
    {
        $this->dfe = $dfe;
        return $this;
    }

    public function getRc(): ?string
    {
        return $this->rc;
    }

    public function setRc(?string $rc): self
    {
        $this->rc = $rc;
        return $this;
    }

    /**
     * Get the value of acd
     */ 
    public function getAcd()
    {
        return $this->acd;
    }

    /**
     * Set the value of acd
     *
     * @return  self
     */ 
    public function setAcd($acd)
    {
        $this->acd = $acd;

        return $this;
    }

    /**
     * Get the value of plan
     */ 
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Set the value of plan
     *
     * @return  self
     */ 
    public function setPlan($plan)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get the value of topographique
     */ 
    public function getTopographique()
    {
        return $this->topographique;
    }

    /**
     * Set the value of topographique
     *
     * @return  self
     */ 
    public function setTopographique($topographique)
    {
        $this->topographique = $topographique;

        return $this;
    }

    /**
     * Get the value of facture
     */ 
    public function getFacture()
    {
        return $this->facture;
    }

    /**
     * Set the value of facture
     *
     * @return  self
     */ 
    public function setFacture($facture)
    {
        $this->facture = $facture;

        return $this;
    }

    /**
     * Get the value of situation
     */ 
    public function getSituation()
    {
        return $this->situation;
    }

    /**
     * Set the value of situation
     *
     * @return  self
     */ 
    public function setSituation($situation)
    {
        $this->situation = $situation;

        return $this;
    }

    /**
     * Get the value of certificat
     */ 
    public function getCertificat()
    {
        return $this->certificat;
    }

    /**
     * Set the value of certificat
     *
     * @return  self
     */ 
    public function setCertificat($certificat)
    {
        $this->certificat = $certificat;

        return $this;
    }

    /**
     * Get the value of attestation
     */ 
    public function getAttestation()
    {
        return $this->attestation;
    }

    /**
     * Set the value of attestation
     *
     * @return  self
     */ 
    public function setAttestation($attestation)
    {
        $this->attestation = $attestation;

        return $this;
    }

    /**
     * Get the value of photoRespo
     */ 
    public function getPhotoRespo()
    {
        return $this->photoRespo;
    }

    /**
     * Set the value of photoRespo
     *
     * @return  self
     */ 
    public function setPhotoRespo($photoRespo)
    {
        $this->photoRespo = $photoRespo;

        return $this;
    }

    /**
     * Get the value of attestationInscription
     */ 
    public function getAttestationInscription()
    {
        return $this->attestationInscription;
    }

    /**
     * Set the value of attestationInscription
     *
     * @return  self
     */ 
    public function setAttestationInscription($attestationInscription)
    {
        $this->attestationInscription = $attestationInscription;

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
}
