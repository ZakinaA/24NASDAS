<?php

namespace App\Entity;

use App\Repository\CouleurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouleurRepository::class)]
class Couleur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Instrument>
     */
    #[ORM\OneToMany(targetEntity: Instrument::class, mappedBy: 'couleur')]
    private Collection $instrument;

    public function __construct()
    {
        $this->instrument = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection<int, Instrument>
     */
    public function getInstrument(): Collection
    {
        return $this->instrument;
    }

    public function addInstrument(Instrument $instrument): static
    {
        if (!$this->instrument->contains($instrument)) {
            $this->instrument->add($instrument);
            $instrument->setCouleur($this);
        }

        return $this;
    }

    public function removeInstrument(Instrument $instrument): static
    {
        if ($this->instrument->removeElement($instrument)) {
            // set the owning side to null (unless already changed)
            if ($instrument->getCouleur() === $this) {
                $instrument->setCouleur(null);
            }
        }

        return $this;
    }
}
