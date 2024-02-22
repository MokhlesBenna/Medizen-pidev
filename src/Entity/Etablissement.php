<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: EtablissementRepository::class)]
class Etablissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank (message:'champ obligatoire')]
    #[Assert\Regex(
        pattern: '/^[a-z]+$/i',
        message: 'lEtablissement ne contient pas des nombre',
        match: true
    )]
    private ?string $name = null;
    

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank (message:'champ obligatoire') ]

    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank (message:'champ obligatoire') ]

    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank (message:'champ obligatoire') ]
    #[Assert\Length(
        min: 50,
        max: 255,
        minMessage: 'insuffisant {{ limit }}',
        maxMessage: 'trop long {{ limit }} ',
    )]
    private ?string $description = null;

    #[ORM\OneToMany(targetEntity: Departement::class, mappedBy: 'etablissement')]
    private Collection $departementList;

    public function __construct()
    {
        $this->departementList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

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

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Departement>
     */
    public function getDepartementList(): Collection
    {
        return $this->departementList;
    }

    public function addDepartementList(Departement $departementList): static
    {
        if (!$this->departementList->contains($departementList)) {
            $this->departementList->add($departementList);
            $departementList->setEtablissement($this);
        }

        return $this;
    }

    public function removeDepartementList(Departement $departementList): static
    {
        if ($this->departementList->removeElement($departementList)) {
            // set the owning side to null (unless already changed)
            if ($departementList->getEtablissement() === $this) {
                $departementList->setEtablissement(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->name;
    }
}
