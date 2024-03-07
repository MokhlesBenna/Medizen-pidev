<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank(message="Ce champ titre doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      max = 100,
     *      minMessage = "Le nom doit dépasser les 5 caractères",
     *      maxMessage = "Le nom ne doit pas dépasser 50 caractères"
     * )
     * @Assert\Regex(
     *     pattern="/\d/",
     *     match=false,
     *     message="Ce champ ne doit pas contenir de chiffres."
     * )
     */
    private ?string $titre = null;

    #[ORM\Column(type:"datetime", nullable:true)]
    /**
     * @Assert\NotBlank(message="Ce champ date doit être renseigné")
     * @Assert\GreaterThanOrEqual("today", message="Vérifiez votre date")
     */
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type:"datetime",nullable:true)]
    /**
     * @Assert\NotBlank(message="Ce champ date de fin doit être renseigné")
     * @Assert\GreaterThanOrEqual(propertyPath="dateDebut", message="La date de fin doit être postérieure à la date de début")
     */
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank(message="Ce champ lieu doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      max = 100,
     *      minMessage = "Précisez le lieu",
     *      maxMessage = "Ce champ ne doit pas dépasser 100 caractères"
     * )
     */
    private ?string $lieu = null;

    #[ORM\Column(type:"text")]
    /**
     * @Assert\NotBlank(message="Ce champ description doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      minMessage = "Donnez une description détaillée"
     * )
     */
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    
    private ?string $image = null;

    #[ORM\ManyToMany(targetEntity: Sponseur::class, inversedBy: 'events')]
    private Collection $sponsor_list;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'event')]
    private Collection $users;

 

    public function __construct()
    {
        $this->sponsor_list = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->dateDebut = $this->getCurrentDate();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Sponseur>
     */
    public function getSponsorList(): Collection
    {
        return $this->sponsor_list;
    }

    public function addSponsorList(Sponseur $sponsorList): static
    {
        if (!$this->sponsor_list->contains($sponsorList)) {
            $this->sponsor_list->add($sponsorList);
        }

        return $this;
    }

    public function removeSponsorList(Sponseur $sponsorList): static
    {
        $this->sponsor_list->removeElement($sponsorList);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getEvent(): Collection
    {
        return $this->event;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addEvent($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeEvent($this);
        }

        return $this;
    }
public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
   
}