<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: 'date')]
#[Assert\NotBlank(message: "Veuillez choisir une date valide!.")]
#[Assert\GreaterThanOrEqual("today", message:"Vérifiez votre date.")]
private ?\DateTime $reservation_date = null;

    #[ORM\Column(length: 255)]
    private ?string $RemarquesMedecin = null;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getRemarquesMedecin(): ?string
    {
        return $this->RemarquesMedecin;
    }

    public function setRemarquesMedecin(string $RemarquesMedecin): static
    {
        $this->RemarquesMedecin = $RemarquesMedecin;

        return $this;
    }
}
