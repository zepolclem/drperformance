<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModelRepository")
 */
class Model
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $resume;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Manufacturer", inversedBy="carModels")
     */
    private $manufacturer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Generation", mappedBy="Model", orphanRemoval=true)
     */
    private $generations;

    public function __construct()
    {
        $this->generations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(?string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function getManufacturer(): ?Manufacturer
    {
        return $this->manufacturer;
    }

    public function setManufacturer(?Manufacturer $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * @return Collection|Generation[]
     */
    public function getGenerations(): Collection
    {
        return $this->generations;
    }

    public function addGeneration(Generation $generation): self
    {
        if (!$this->generations->contains($generation)) {
            $this->generations[] = $generation;
            $generation->setModel($this);
        }

        return $this;
    }

    public function removeGeneration(Generation $generation): self
    {
        if ($this->generations->contains($generation)) {
            $this->generations->removeElement($generation);
            // set the owning side to null (unless already changed)
            if ($generation->getModel() === $this) {
                $generation->setModel(null);
            }
        }

        return $this;
    }
}