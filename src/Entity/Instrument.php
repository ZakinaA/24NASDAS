<?php

namespace App\Entity;

use App\Repository\InstrumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstrumentRepository::class)]
class Instrument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numSerie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateAchat = null;

    #[ORM\Column(nullable: true)]
    private ?int $prixAchat = null;

    #[ORM\Column(nullable: true)]
    private ?bool $utilisation = null;

    #[ORM\Column(length: 250, nullable: true)]
    private ?string $cheminImage = null;

    #[ORM\ManyToOne]
    private ?TypeInstrument $type_instrument = null;

    /**
     * @var Collection<int, Accessoire>
     */
    #[ORM\ManyToMany(targetEntity: Accessoire::class, inversedBy: 'instrument')]
    private Collection $accessoire;

    public function __construct()
    {
        $this->accessoire = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumSerie(): ?int
    {
        return $this->numSerie;
    }

    public function setNumSerie(int $numSerie): static
    {
        $this->numSerie = $numSerie;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->dateAchat;
    }

    public function setDateAchat(?\DateTimeInterface $dateAchat): static
    {
        $this->dateAchat = $dateAchat;

        return $this;
    }

    public function getPrixAchat(): ?int
    {
        return $this->prixAchat;
    }

    public function setPrixAchat(?int $prixAchat): static
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }

    public function isUtilisation(): ?bool
    {
        return $this->utilisation;
    }

    public function setUtilisation(?bool $utilisation): static
    {
        $this->utilisation = $utilisation;

        return $this;
    }

    public function getCheminImage(): ?string
    {
        return $this->cheminImage;
    }

    public function setCheminImage(?string $cheminImage): static
    {
        $this->cheminImage = $cheminImage;

        return $this;
    }

    public function getTypeInstrument(): ?TypeInstrument
    {
        return $this->type_instrument;
    }

    public function setTypeInstrument(?TypeInstrument $type_instrument): static
    {
        $this->type_instrument = $type_instrument;

        return $this;
    }

    /**
     * @return Collection<int, Accessoire>
     */
    public function getAccessoire(): Collection
    {
        return $this->accessoire;
    }

    public function addAccessoire(Accessoire $accessoire): static
    {
        if (!$this->accessoire->contains($accessoire)) {
            $this->accessoire->add($accessoire);
        }

        return $this;
    }

    public function removeAccessoire(Accessoire $accessoire): static
    {
        $this->accessoire->removeElement($accessoire);

        return $this;
    }
}
