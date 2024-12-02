<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TypeInstrumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: TypeInstrumentRepository::class)]
class TypeInstrument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    /**
     * @var Collection<int, Professeur>
     */
    #[ORM\OneToMany(targetEntity: Professeur::class, mappedBy: 'type_instrument')]
    private Collection $professeur;

    public function __construct()
    {
        $this->professeur = new ArrayCollection();
    }

    // Méthodes getter et setter...
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
     * @return Collection<int, Professeur>
     */
    public function getProfesseur(): Collection
    {
        return $this->professeur;
    }

    public function addProfesseur(Professeur $professeur): static
    {
        if (!$this->professeur->contains($professeur)) {
            $this->professeur->add($professeur);
            $professeur->setTypeInstrument($this);
        }

        return $this;
    }

    public function removeProfesseur(Professeur $professeur): static
    {
        if ($this->professeur->removeElement($professeur)) {
            // set the owning side to null (unless already changed)
            if ($professeur->getTypeInstrument() === $this) {
                $professeur->setTypeInstrument(null);
            }
        }

        return $this;
    }
}
