<?php

namespace App\Entity;

use App\Repository\SponseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SponseurRepository::class)]
class Sponseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank(message="Ce champ titre doit etre non vide")
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "doit etre <=200" 
     * )
     * @Assert\Regex(
     *      pattern="/\d/",
     *      match=false,
     *      message="Ce champ ne doit pas contenir de chiffres."
     * )
     */
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank(message="Ce champ e-mail doit être renseigné")
     * @Assert\Email(
     *     message="L'adresse e-mail '{{ value }}' n'est pas valide."
     * )
     */
    private ?string $email = null;

    #[ORM\Column]
    /**
     * @Assert\NotBlank(message="Ce champ titre doit etre non vide")
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "donner un numéro valide ",
     *      maxMessage = "Le numéro ne depasse pas 8 chiffres " 
     * )
     */
    private ?int $numero = null;

    #[ORM\Column(length: 255)]
    /**
     * @Assert\NotBlank(message="Ce champ doit être renseigné")
     * @Assert\Url(
     *     message="L'URL '{{ value }}' n'est pas une URL valide."
     * )
     */
    private ?string $logo = null;

    #[ORM\Column(length: 255)]
    private ?string $pack = null;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'sponsor_list')]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getPack(): ?string
    {
        return $this->pack;
    }

    public function setPack(string $pack): static
    {
        $this->pack = $pack;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->addSponsorList($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            $event->removeSponsorList($this);
        }

        return $this;
    }
}
