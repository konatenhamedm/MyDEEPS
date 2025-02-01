<?php

namespace App\Entity;

use App\Repository\SpecialiteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups as Group;

#[ORM\Entity(repositoryClass: SpecialiteRepository::class)]
class Specialite
{
    use TraitEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Group(["group1"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Group(["group1"])]
    private ?string $libelle = null;

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
}
