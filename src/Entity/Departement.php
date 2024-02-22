<?php

namespace App\Entity;

use App\Repository\DepartementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
class Departement
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
    private ?string $Nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank (message:'champ obligatoire') ]
    #[Assert\Length(
        min: 50,
        max: 255,
        minMessage: 'insuffisant {{ limit }}',
        maxMessage: 'trop long {{ limit }} ',
    )]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank (message:'champ obligatoire') ]

    private ?string $chefDepartement = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank (message:'champ obligatoire') ]

    private ?string $servicesOfferts = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank (message:'champ obligatoire') ]

    private ?string $localisation = null;

    #[ORM\ManyToOne(inversedBy: 'departementList')]
    private ?Etablissement $etablissement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

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

    public function getChefDepartement(): ?string
    {
        return $this->chefDepartement;
    }

    public function setChefDepartement(?string $chefDepartement): static
    {
        $this->chefDepartement = $chefDepartement;

        return $this;
    }

    public function getServicesOfferts(): ?string
    {
        return $this->servicesOfferts;
    }

    public function setServicesOfferts(?string $servicesOfferts): static
    {
        $this->servicesOfferts = $servicesOfferts;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getEtablissement(): ?Etablissement
    {
        return $this->etablissement;
    }

    public function setEtablissement(?Etablissement $etablissement): static
    {
        $this->etablissement = $etablissement;

        return $this;
    }
    public function __toString(){
        return $this->Nom;
    }
}
