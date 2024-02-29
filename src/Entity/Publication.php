<?php

namespace App\Entity;
use Symfony\Component\HttpFoundation\File\File;
use App\Repository\PublicationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: PublicationRepository::class)]
class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min:6,max: 250, minMessage:"Le contenu doit avoir au moins 6 caractères.",
         maxMessage:"Le contenu ne peut pas dépasser 250 caractères.")]
    private ?string $Contenu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(propertyPath: "currentDate", message: "Date deja passée,veuillez verifier")]
    private ?\DateTimeInterface $Datedecreation = null;
    public function getCurrentDate(): \DateTimeInterface
    {
        return new \DateTime();
    }

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"L'image ne peut pas être vide.")]
    
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'PublicationList')]
    #[ORM\JoinColumn(nullable: false)]
    
    private ?Topic $topic ;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): static
    {
        $this->topic = $topic;

        return $this;
    }
    public function setTopicId($topicId)
{
    $this->topicId = $topicId;
}

}
