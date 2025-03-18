<?php

namespace App\Entity;

use App\Repository\ProfessionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups as Group;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ProfessionRepository::class)]
#[UniqueEntity(fields: 'libelle', message: 'Cette profession existe deja')]
class Profession
{

    use TraitEntity; 

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Group(["group1","group2"])]
    private ?int $id = null;


    #[Group(["group1","group2"])]
    #[ORM\Column(type: 'string', unique: true, nullable: true,length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    #[Group(["group1","group2"])]
    private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'professions')]
    #[Group(["group1"])]
    private ?TypeProfession $typeProfession = null;

    #[ORM\Column(length: 255)]
    #[Group(["group1","group2"])]
    private ?string $montantNouvelleDemande = null;

    #[ORM\Column(length: 255)]
    #[Group(["group1","group2"])]
    private ?string $montantRenouvellement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getTypeProfession(): ?TypeProfession
    {
        return $this->typeProfession;
    }

    public function setTypeProfession(?TypeProfession $typeProfession): static
    {
        $this->typeProfession = $typeProfession;

        return $this;
    }

    public function getMontantNouvelleDemande(): ?string
    {
        return $this->montantNouvelleDemande;
    }

    public function setMontantNouvelleDemande(string $montantNouvelleDemande): static
    {
        $this->montantNouvelleDemande = $montantNouvelleDemande;

        return $this;
    }

    public function getMontantRenouvellement(): ?string
    {
        return $this->montantRenouvellement;
    }

    public function setMontantRenouvellement(string $montantRenouvellement): static
    {
        $this->montantRenouvellement = $montantRenouvellement;

        return $this;
    }
}
