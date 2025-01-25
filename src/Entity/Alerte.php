<?php

namespace App\Entity;

use App\Repository\AlerteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups as Group;


#[ORM\Entity(repositoryClass: AlerteRepository::class)]
class Alerte
{
    use TraitEntity; 

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'alertes')]
    private ?User $user = null;

    #[ORM\Column(type: "string", length: 255)]
    private string $objet;

    #[ORM\Column(type: Types::TEXT)]
    private string $message;

    #[ORM\Column(type: "string", length: 255)]
    private string $destinateur;

    #[ORM\Column(type: "string", length: 255)]
    private string $lecteur;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date;


    public function __construct()
    {

        $this->date = new \DateTime(); // Date par défaut
    }


    public function getObjet(): string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getDestinateur(): string
    {
        return $this->destinateur;
    }

    public function setDestinateur(string $destinateur): self
    {
        $this->destinateur = $destinateur;
        return $this;
    }

    public function getLecteur(): string
    {
        return $this->lecteur;
    }

    public function setLecteur(string $lecteur): self
    {
        $this->lecteur = $lecteur;
        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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
