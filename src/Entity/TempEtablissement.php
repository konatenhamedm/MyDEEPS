<?php

namespace App\Entity;

use App\Repository\TempEtablissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?string $reference = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\Column( nullable: true)]
    #[Group(['group_pro'])]
    private ?string $appartenirOrganisation = null;

   

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $reason = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $status = null;

    
    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_pro"])]
    private ?string $typePersonne = null;

    /**
     * @var Collection<int, DocumentTemporaire>
     */
    #[ORM\OneToMany(targetEntity: DocumentTemporaire::class, mappedBy: 'tempEtablissement')]
    private Collection $documentTemporaires;

    #[ORM\Column(length: 255)]
    private ?string $typeUser = null;

    public function __construct()
    {
        $this->documentTemporaires = new ArrayCollection();
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
     * Get the value of reference
     */ 
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set the value of reference
     *
     * @return  self
     */ 
    public function setReference($reference)
    {
        $this->reference = $reference;

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
     * @return Collection<int, DocumentTemporaire>
     */
    public function getDocumentTemporaires(): Collection
    {
        return $this->documentTemporaires;
    }

    public function addDocumentTemporaire(DocumentTemporaire $documentTemporaire): static
    {
        if (!$this->documentTemporaires->contains($documentTemporaire)) {
            $this->documentTemporaires->add($documentTemporaire);
            $documentTemporaire->setTempEtablissement($this);
        }

        return $this;
    }

    public function removeDocumentTemporaire(DocumentTemporaire $documentTemporaire): static
    {
        if ($this->documentTemporaires->removeElement($documentTemporaire)) {
            // set the owning side to null (unless already changed)
            if ($documentTemporaire->getTempEtablissement() === $this) {
                $documentTemporaire->setTempEtablissement(null);
            }
        }

        return $this;
    }

    public function getTypeUser(): ?string
    {
        return $this->typeUser;
    }

    public function setTypeUser(string $typeUser): static
    {
        $this->typeUser = $typeUser;

        return $this;
    }
}
