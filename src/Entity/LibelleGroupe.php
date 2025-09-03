<?php

namespace App\Entity;

use App\Repository\LibelleGroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups as Group;


#[ORM\Entity(repositoryClass: LibelleGroupeRepository::class)]
class LibelleGroupe
{

    use TraitEntity; 

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Group(["group1","group_pro","group_libelle"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Group(["group1","group_pro","group_libelle"])]
    private ?string $libelle = null;

    /**
     * @var Collection<int, TypeDocument>
     */
    #[ORM\OneToMany(targetEntity: TypeDocument::class, mappedBy: 'libelleGroupe')]
    #[Group(["group_libelle"])]
    private Collection $typeDocuments;

    /**
     * @var Collection<int, Document>
     */
    #[ORM\OneToMany(targetEntity: Document::class, mappedBy: 'libelleGroupe')]
    private Collection $documents;

    /**
     * @var Collection<int, DocumentTemporaire>
     */
    #[ORM\OneToMany(targetEntity: DocumentTemporaire::class, mappedBy: 'libelleGroupe')]
    private Collection $documentTemporaires;

    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group1","group_libelle"])]
    private ?string $type = null;

    public function __construct()
    {
        $this->typeDocuments = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->documentTemporaires = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, TypeDocument>
     */
    public function getTypeDocuments(): Collection
    {
        return $this->typeDocuments;
    }

    public function addTypeDocument(TypeDocument $typeDocument): static
    {
        if (!$this->typeDocuments->contains($typeDocument)) {
            $this->typeDocuments->add($typeDocument);
            $typeDocument->setLibelleGroupe($this);
        }

        return $this;
    }

    public function removeTypeDocument(TypeDocument $typeDocument): static
    {
        if ($this->typeDocuments->removeElement($typeDocument)) {
            // set the owning side to null (unless already changed)
            if ($typeDocument->getLibelleGroupe() === $this) {
                $typeDocument->setLibelleGroupe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): static
    {
        if (!$this->documents->contains($document)) {
            $this->documents->add($document);
            $document->setLibelleGroupe($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): static
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getLibelleGroupe() === $this) {
                $document->setLibelleGroupe(null);
            }
        }

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
            $documentTemporaire->setLibelleGroupe($this);
        }

        return $this;
    }

    public function removeDocumentTemporaire(DocumentTemporaire $documentTemporaire): static
    {
        if ($this->documentTemporaires->removeElement($documentTemporaire)) {
            // set the owning side to null (unless already changed)
            if ($documentTemporaire->getLibelleGroupe() === $this) {
                $documentTemporaire->setLibelleGroupe(null);
            }
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
