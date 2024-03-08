<?php

namespace App\Entity;

use App\Repository\ConsultationPatientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConsultationPatientRepository::class)]
class ConsultationPatient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom est requis")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom est requis")]
    private ?string $surname = null;

    #[ORM\Column( nullable: true)]
    private ?string $RemarquesDesDocteurs = null;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getRemarquesDesDocteurs(): ?string
    {
        return $this->RemarquesDesDocteurs;
    }

    public function setRemarquesDesDocteurs(?string $RemarquesDesDocteurs): static
    {
        $this->RemarquesDesDocteurs = $RemarquesDesDocteurs;

        return $this;
    }

   
    }
