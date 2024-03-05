<?php

namespace App\Entity;

use App\Repository\ConsultationPatientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationPatientRepository::class)]
class ConsultationPatient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $surname = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    public ?Reservation $reservation_date = null;

    #[ORM\Column(length: 255)]
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

    public function getReservationDate(): ?reservation
    {
        return $this->reservation_date;
    }

    public function setReservationDate(?reservation $reservation_date): static
    {
        $this->reservation_date = $reservation_date;

        return $this;
    }

    public function getRemarquesDesDocteurs(): ?string
    {
        return $this->RemarquesDesDocteurs;
    }

    public function setRemarquesDesDocteurs(string $RemarquesDesDocteurs): static
    {
        $this->RemarquesDesDocteurs = $RemarquesDesDocteurs;

        return $this;
    }

    
}
