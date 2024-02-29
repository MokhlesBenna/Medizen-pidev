<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TopicRepository::class)]
class Topic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
#[Assert\NotBlank(message:"Le titre ne peut pas être vide.")]
#[Assert\Length(min: 5, max: 30)]
#[Assert\Regex(pattern: '/^[^.]/', message: "Le titre ne doit pas commencer par un point.")]
private ?string $Titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le contenu ne peut pas être vide.")]
    #[Assert\Length(min:6,max: 250)]
    private ?string $Contenu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(propertyPath: "currentDate", message: "Date deja passée,veuillez vérifier")]
    private ?\DateTimeInterface $Datedecreation = null;

    #[ORM\OneToMany(targetEntity: Publication::class, mappedBy: 'topic')]
    private Collection $PublicationList;

    public function __construct()
    {
        $this->PublicationList = new ArrayCollection();
        $this->Datedecreation = $this->getCurrentDate();
    }

   

    public function getCurrentDate(): \DateTimeInterface
    {
        return new \DateTime();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): static
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->Contenu;
    }

    public function setContenu(string $Contenu): static
    {
        $this->Contenu = $Contenu;

        return $this;
    }

    public function getDatedecreation(): ?\DateTimeInterface
    {
        return $this->Datedecreation;
    }

    public function setDatedecreation(\DateTimeInterface $Datedecreation): static
    {
        $this->Datedecreation = $Datedecreation;

        return $this;
    }

    /**
     * @return Collection<int, Publication>
     */
    public function getPublicationList(): Collection
    {
        return $this->PublicationList;
    }

    public function addPublicationList(Publication $publicationList): static
    {
        if (!$this->PublicationList->contains($publicationList)) {
            $this->PublicationList->add($publicationList);
            $publicationList->setTopic($this);
        }

        return $this;
    }

    public function removePublicationList(Publication $publicationList): static
    {
        if ($this->PublicationList->removeElement($publicationList)) {
            // set the owning side to null (unless already changed)
            if ($publicationList->getTopic() === $this) {
                $publicationList->setTopic(null);
            }
        }

        return $this;
    }

   
    
}
