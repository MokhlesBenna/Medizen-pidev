<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Datedecreation = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $nbrlikes = null;

    #[ORM\Column]
    private ?int $nbrdislikes = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?Publication $publication = null;

    #[ORM\ManyToOne(inversedBy: 'commentaires')]
    private ?User $id_user = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNbrlikes(): ?int
    {
        return $this->nbrlikes;
    }

    public function setNbrlikes(int $nbrlikes): static
    {
        $this->nbrlikes = $nbrlikes;

        return $this;
    }

    public function getNbrdislikes(): ?int
    {
        return $this->nbrdislikes;
    }

    public function setNbrdislikes(int $nbrdislikes): static
    {
        $this->nbrdislikes = $nbrdislikes;

        return $this;
    }

    public function getPublication(): ?Publication
    {
        return $this->publication;
    }

    public function setPublication(?Publication $publication): static
    {
        $this->publication = $publication;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }
}
