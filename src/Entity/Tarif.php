<?php

namespace App\Entity;

use App\Repository\TarifRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TarifRepository::class)]
class Tarif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $montant = null;

    #[ORM\ManyToOne(inversedBy: 'tarifs')]
    private ?TypeCours $typeCours = null;

    #[ORM\ManyToOne(inversedBy: 'tarifs')]
    private ?QuotientFamilial $quotientFamilial = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getTypeCours(): ?TypeCours
    {
        return $this->typeCours;
    }

    public function setTypeCours(?TypeCours $typeCours): static
    {
        $this->typeCours = $typeCours;

        return $this;
    }

    public function getQuotientFamilial(): ?QuotientFamilial
    {
        return $this->quotientFamilial;
    }

    public function setQuotientFamilial(?QuotientFamilial $quotientFamilial): static
    {
        $this->quotientFamilial = $quotientFamilial;

        return $this;
    }
}
