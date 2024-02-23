<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\MedicamentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: MedicamentRepository::class)]
#[Vich\Uploadable]
class Medicament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please provide a name for the medication.')]
    #[Assert\Length(max: 255, maxMessage: 'Name cannot be longer than 10 characters.')]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Please specify the quantity of the medication.')]
    #[Assert\Type(type: 'integer', message: 'Quantity must be a whole number.')]
    #[Assert\GreaterThan(value: 0, message: 'Quantity must be greater than 0.')]
    private ?int $quantity = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Please provide a description for the medication.')]
    #[Assert\Length(
        max: 255,
        maxMessage: 'Description cannot be longer than {{ limit }} characters. Please provide a brief description.'
    )]
    private ?string $description = null;

    /**
     * @Vich\UploadableField(mapping="medicament_images", fileNameProperty="image")
     */
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, options: ['default' => 'default_image.jpg'])]
    private ?string $image = 'default_image.jpg';

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Please specify the price of the medication.')]
    #[Assert\Type(type: 'float', message: 'Price must be a valid number.')]
    #[Assert\GreaterThan(value: 0, message: 'Price must be greater than 0.')]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'medicament_list')]
    private ?Commande $commande = null;

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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): void
    {
        $this->imageFile = $imageFile;

     
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }
}
