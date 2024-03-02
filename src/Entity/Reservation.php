<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    private ?string $surname = null;




    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La description du problème est obligatoire.")]
    private ?string $problem_description = null;
    
    #[ORM\Column(type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: "Le numéro de téléphone est obligatoire.")]
    #[Assert\Regex(
        pattern: '/^\d{8}$/',
        message: "Le numéro de téléphone doit être composé de 8 chiffres."
    )]
    private ?int $mobile = null;
    

    #[ORM\Column(type: 'date')]
#[Assert\NotBlank(message: "Veuillez choisir une date valide!.")]
#[Assert\GreaterThanOrEqual("today", message:"Vérifiez votre date.")]
private ?\DateTime $reservation_date = null;
    

        public function setReservationDate(\DateTime $reservation_date): bool
        {
            $today = new \DateTime();
            if ($reservation_date >= $today) {
                $this->reservation_date = $reservation_date;
                return true;
            } else {
                
                return false;
            }
        }
        

    #[ORM\Column(length: 255)]
    private ?string $id_user = "700";

    #[ORM\Column(length: 255)]
    private ?string $status ="pending";

    #[Assert\NotBlank(message: 'Le nom est obligatoire.')]
    #[Assert\Length(max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotBlank(message: 'L\'adresse est obligatoire.')]
    #[Assert\Length(max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $address = null;
    
    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Docteur $doctor = null;

    
   

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }


    public function getProblemDescription(): ?string
    {
        return $this->problem_description;
    }

    public function setProblemDescription(string $problem_description): static
    {
        $this->problem_description = $problem_description;

        return $this;
    }

    public function getMobile(): ?int
    {
        return $this->mobile;
    }

    public function setMobile(int $mobile): static
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getReservationDate(): ?\DateTime
    {
        return $this->reservation_date;
    }


    public function getIdUser(): ?string
    {
        return $this->id_user;
    }

    public function setIdUser(string $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDoctor(): ?Docteur
    {
        return $this->doctor;
    }

    public function setDoctor(?Docteur $doctor): static
    {
        $this->doctor = $doctor;

        return $this;
    }

   

   

   
   
}
