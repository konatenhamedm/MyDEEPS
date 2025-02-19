<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups as Group;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[Table(name: 'utilisateur')]
#[UniqueEntity(fields: 'email', message: 'Ce email est déjà utilisé par un autre utilisateur,veillez vous connecter')]
#[UniqueEntity(fields: 'username', message: 'Ce username est déjà utilisé par un autre utilisateur,veillez vous connecter')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    const STATUS = [
        'ENABLE' => "ENABLE",
        'ACTIVE' => "ACTIVE",
        'ACCEPT' => "ACCEPT",

    ];

    const PAYEMENT = [
        'init_payement' => "init_payement",
        'payed' => "payed",
        'payed_inifinty' => "payed_inifinty",

    ];
    const TYPE = [
        'ETABLISSEMENT' => "ETABLISSEMENT",
        'PROFESSIONNEL' => "PROFESSIONNEL",
        'ADMINISTRATEUR' => "ADMINISTRATEUR",
    ];


    use TraitEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Group(["group1", "group_user", 'group_pro'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', unique: true, nullable: true)]
    #[Group(["group1", "group_user", 'group_pro'])]
    private ?string $username = null;

    #[ORM\Column(type: 'string', unique: true, nullable: true)]
    #[Assert\Email]
    #[Group(["group1", "group_user", 'group_pro'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Group(["group_user"])]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;


    /**
     * @var Collection<int, Alerte>
     */
    #[ORM\OneToMany(targetEntity: Alerte::class, mappedBy: 'user')]
    private Collection $alertes;

    /**
     * @var Collection<int, Article>
     */
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'user')]
    private Collection $articles;

    /**
     * @var Collection<int, Commentaire>
     */
    #[ORM\OneToMany(targetEntity: Commentaire::class, mappedBy: 'user')]
    private Collection $commentaires;



    #[ORM\ManyToOne(cascade: ["persist"], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Group(["group_user"])]
    private ?Fichier $avatar = null;



    #[ORM\Column(length: 255, nullable: true)]
    #[Group(["group_user", 'group_pro'])]
    private ?string $typeUser = null;

    #[ORM\Column]
    private ?string $payement = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $data = null;


    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'sender')]
    private Collection $messages;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'user')]
    private Collection $transactions;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Entite $personne = null;


    public function __construct()
    {
        $this->alertes = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        /*      if(in_array("ROLE_ADMIN", $this->getRoles())){

            $this->status = "ACCEPT"; //ACTIVE  ACCEPT
            $this->payement = "payed_inifinty"; // payed payed-inifinty
        }else{
            $this->status = "ENABLE"; //ACTIVE  ACCEPT
            $this->payement = "init_payement"; // payed payed-inifinty

        } */
        $this->messages = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->email ?? $this->username ?? '';
    }
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }
    public function getEmail(): ?string
    {
        return $this->email ?? '';
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Vider les données sensibles
    }


    /**
     * @return Collection<int, Alerte>
     */
    public function getAlertes(): Collection
    {
        return $this->alertes;
    }

    public function addAlerte(Alerte $alerte): static
    {
        if (!$this->alertes->contains($alerte)) {
            $this->alertes->add($alerte);
            $alerte->setUser($this);
        }

        return $this;
    }

    public function removeAlerte(Alerte $alerte): static
    {
        if ($this->alertes->removeElement($alerte)) {
            // set the owning side to null (unless already changed)
            if ($alerte->getUser() === $this) {
                $alerte->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUser() === $this) {
                $commentaire->setUser(null);
            }
        }

        return $this;
    }

    public function getAvatar(): ?Fichier
    {
        return $this->avatar;
    }
    public function getLien(): ?string
    {
        return "uploads/" + $this->getAvatar()->getPath() + '/'  + $this->getAvatar()->getAlt();
    }

    public function setAvatar(?Fichier $avatar): static
    {
        $this->avatar = $avatar;
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

    public function getPayement(): ?string
    {
        return $this->payement;
    }

    public function setPayement(string $payement): static
    {
        $this->payement = $payement;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setSender($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getSender() === $this) {
                $message->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setUser($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getUser() === $this) {
                $transaction->setUser(null);
            }
        }

        return $this;
    }

    public function getPersonne(): ?Entite
    {
        return $this->personne;
    }

    public function setPersonne(?Entite $personne): static
    {
        $this->personne = $personne;

        return $this;
    }
}
