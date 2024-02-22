<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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
     *      minMessage = "doit etre >=7 ",
     *      maxMessage = "doit etre <=100" )
     * @ORM\Column(type="string", length=1000)
     */
    private ?string $titre = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    /**
 * @Assert\NotBlank(message="Ce champ date doit être renseigné")
 * @Assert\GreaterThanOrEqual("today", message=" Vérifier votre date  ")
 * @ORM\Column(type="date")
 */
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    /**
 * @Assert\NotBlank(message="Ce champ date doit être renseigné")
 * @Assert\GreaterThanOrEqual("today", message=" Vérifier votre date  ")
 * @ORM\Column(type="date")
 */
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(length: 255)]
    /**
 * @Assert\NotBlank(message="Ce champ lieu doit être non vide")
 * @Assert\Length(
 *      min = 5,
 *      max = 100,
 *      minMessage = "doit être >= 5",
 *      maxMessage = "doit être <= 100"
 * )
 * @Assert\Regex(
 *     pattern="/^[a-zA-Z]+$/",
 *     message="Le champ lieu ne doit contenir que des lettres"
 * )
 * @ORM\Column(type="string", length=1000)
 */
    
    private ?string $lieu = null;

    #[ORM\Column(type: Types::TEXT)]
    /**
     * @Assert\NotBlank(message="Ce champ titre doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      max = 100,
     *      minMessage = "donner une description detaillée ",
     *      maxMessage = "doit etre <=200" )
     * @ORM\Column(type="string", length=1000)
     */
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Sponseur::class)]
    private Collection $sponseurliste;

    public function __construct()
    {
        $this->sponseurliste = new ArrayCollection();
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

    /**
     * @return Collection<int, Sponseur>
     */
    public function getSponseurliste(): Collection
    {
        return $this->sponseurliste;
    }

    public function addSponseurliste(Sponseur $sponseurliste): static
    {
        if (!$this->sponseurliste->contains($sponseurliste)) {
            $this->sponseurliste->add($sponseurliste);
            $sponseurliste->setEvent($this);
        }

        return $this;
    }

    public function removeSponseurliste(Sponseur $sponseurliste): static
    {
        if ($this->sponseurliste->removeElement($sponseurliste)) {
            // set the owning side to null (unless already changed)
            if ($sponseurliste->getEvent() === $this) {
                $sponseurliste->setEvent(null);
            }
        }

        return $this;
    }
}
