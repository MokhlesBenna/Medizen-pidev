<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $totalprice = null;

    #[ORM\Column]
    private ?int $quantity_ordered = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_ordered = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\OneToMany(targetEntity: Medicament::class, mappedBy: 'commande')]
    private Collection $medicament_list;

    public function __construct()
    {
        $this->medicament_list = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalprice(): ?float
    {
        return $this->totalprice;
    }

    public function setTotalprice(float $totalprice): static
    {
        $this->totalprice = $totalprice;

        return $this;
    }

    public function getQuantityOrdered(): ?int
    {
        return $this->quantity_ordered;
    }

    public function setQuantityOrdered(int $quantity_ordered): static
    {
        $this->quantity_ordered = $quantity_ordered;

        return $this;
    }

    public function getDateOrdered(): ?\DateTimeInterface
    {
        return $this->date_ordered;
    }

    public function setDateOrdered(\DateTimeInterface $date_ordered): static
    {
        $this->date_ordered = $date_ordered;

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

    /**
     * @return Collection<int, Medicament>
     */
    public function getMedicamentList(): Collection
    {
        return $this->medicament_list;
    }

    public function addMedicamentList(Medicament $medicamentList): static
    {
        if (!$this->medicament_list->contains($medicamentList)) {
            $this->medicament_list->add($medicamentList);
            $medicamentList->setCommande($this);
        }

        return $this;
    }

    public function removeMedicamentList(Medicament $medicamentList): static
    {
        if ($this->medicament_list->removeElement($medicamentList)) {
            // set the owning side to null (unless already changed)
            if ($medicamentList->getCommande() === $this) {
                $medicamentList->setCommande(null);
            }
        }

        return $this;
    }
    
}
