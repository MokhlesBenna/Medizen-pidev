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

    public function setTotalprice(float $totalprice): self
    {
        $this->totalprice = $totalprice;

        return $this;
    }

    public function getQuantityOrdered(): ?int
    {
        return $this->quantity_ordered;
    }

    public function setQuantityOrdered(int $quantity_ordered): self
    {
        $this->quantity_ordered = $quantity_ordered;

        return $this;
    }

    public function getDateOrdered(): ?\DateTimeInterface
    {
        return $this->date_ordered;
    }

    public function setDateOrdered(\DateTimeInterface $date_ordered): self
    {
        $this->date_ordered = $date_ordered;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
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

    public function addMedicamentList(Medicament $medicamentList): self
    {
        if (!$this->medicament_list->contains($medicamentList)) {
            $this->medicament_list->add($medicamentList);
            $medicamentList->setCommande($this);
        }

        return $this;
    }

    public function removeMedicamentList(Medicament $medicamentList): self
    {
        if ($this->medicament_list->removeElement($medicamentList)) {
            // set the owning side to null (unless already changed)
            if ($medicamentList->getCommande() === $this) {
                $medicamentList->setCommande(null);
            }
        }

        return $this;
    }

    public function calculateTotalPrice(): float
    {
        $totalPrice = 0.0;

        // Iterate through the medicament_list collection
        foreach ($this->medicament_list as $medicament) {
            // Calculate the total price for each medicament and add it to the total
            $totalPrice += $medicament->getPrice() * $medicament->getQuantity();
        }

        return $totalPrice;
    }
}
