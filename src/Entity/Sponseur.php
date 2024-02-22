<?php

namespace App\Entity;

use App\Repository\SponseurRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SponseurRepository::class)]

class Sponseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
     /**
     * @Assert\NotBlank(message="Ce champ titre doit etre non vide")
     * @Assert\Length(
   
     *      max = 100,
   
     *      maxMessage = "doit etre <=200" )
     * @Assert\Regex(
     * pattern="/\d/",
     * match=false,
     * message="Ce champ ne doit pas contenir de chiffres.")
     * @ORM\Column(type="string", length=1000)
     */
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    /**
 * @Assert\NotBlank(message="Ce champ e-mail doit être renseigné")
 * @Assert\Email(
 *     message="L'adresse e-mail '{{ value }}' n'est pas valide."
 * )
 * @ORM\Column(type="string", length=255)
 */
    private ?string $email = null;

    #[ORM\Column]
    /**
     * @Assert\NotBlank(message="Ce champ titre doit etre non vide")
     * @Assert\Length(
     *      min = 8,
     *      max = 100,
     *      minMessage = "donner un numéro valide ",
     *      maxMessage = "doit etre <=200" )
     * @ORM\Column(type="string", length=1000)
     */
    private ?int $numero = null;

    #[ORM\Column(length: 255)]
    /**
 * @Assert\NotBlank(message="Ce champ doit être renseigné")
 * @Assert\Url(
 *     message="L'URL '{{ value }}' n'est pas une URL valide."
 * )
 * @ORM\Column(type="string", length=255)
 */
    private ?string $logo = null;

    #[ORM\Column(length: 255)]
    /**
 * @Assert\NotBlank(message="Ce champ doit être renseigné")
 * @Assert\Length(
 *      max = 100,
 *      maxMessage = "ce champs ne depasse pas 100 caractères"
 * )
 * @Assert\Regex(
 *     pattern="/\d/",
 *     match=false,
 *     message="Ce champ ne doit pas contenir de chiffres."
 * )
 * @ORM\Column(type="string", length=1000)
 */
    private ?string $pack = null;

    #[ORM\ManyToOne(inversedBy: 'sponseurliste')]
    private ?Event $event = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getPack(): ?string
    {
        return $this->pack;
    }

    public function setPack(string $pack): static
    {
        $this->pack = $pack;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): static
    {
        $this->event = $event;

        return $this;
    }
}
