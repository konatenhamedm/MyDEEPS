<?php

namespace App\Entity;

use App\Repository\EntiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\Table;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups as Group;




#[ORM\Entity(repositoryClass: EntiteRepository::class)]
#[Table(name: 'membre_entite')]
#[InheritanceType("JOINED")]
#[DiscriminatorColumn(name: "discr", type: "string", length: 18)]
#[DiscriminatorMap([
    'entite' => Entite::class,
    'professionnel' => Professionnel::class,
    'etablissement' => Etablissement::class
])]
class Entite
{

    use TraitEntity; 

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Group(['group_pro'])]
    private ?int $id = null;





    #[ORM\Column]
    #[Group(['group_pro'])]
    private ?string $appartenirOrganisation = null;

    /**
     * @var Collection<int, Organisation>
     */
    #[ORM\OneToMany(targetEntity: Organisation::class, mappedBy: 'entite')]
    private Collection $organisations;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Group(['group_pro'])]
    private ?User $user = null;

    public function __construct()
    {
        $this->organisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }




    public function getAppartenirOrganisation(): ?string
    {
        return $this->appartenirOrganisation;
    }

    public function setAppartenirOrganisation(string $appartenirOrganisation): static
    {
        $this->appartenirOrganisation = $appartenirOrganisation;

        return $this;
    }

    /**
     * @return Collection<int, Organisation>
     */
    public function getOrganisations(): Collection
    {
        return $this->organisations;
    }

    public function addOrganisation(Organisation $organisation): static
    {
        if (!$this->organisations->contains($organisation)) {
            $this->organisations->add($organisation);
            $organisation->setEntite($this);
        }

        return $this;
    }

    public function removeOrganisation(Organisation $organisation): static
    {
        if ($this->organisations->removeElement($organisation)) {
            // set the owning side to null (unless already changed)
            if ($organisation->getEntite() === $this) {
                $organisation->setEntite(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

}
