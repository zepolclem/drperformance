<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EngineRepository")
 */
class Engine
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $power;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $torque;

    /**
     * @ORM\Column(type="EngineEnergyType", nullable=true)
     */
    private $energy;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cylinderCapacity;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $turbo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Generation", inversedBy="engines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $generation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Map", mappedBy="engine", orphanRemoval=true)
     */
    private $maps;

    public function __construct()
    {
        $this->maps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPower(): ?int
    {
        return $this->power;
    }

    public function setPower(?int $power): self
    {
        $this->power = $power;

        return $this;
    }

    public function getTorque(): ?int
    {
        return $this->torque;
    }

    public function setTorque(?int $torque): self
    {
        $this->torque = $torque;

        return $this;
    }

    public function getEnergy()
    {
        return $this->energy;
    }

    public function setEnergy($energy): self
    {
        $this->energy = $energy;

        return $this;
    }

    public function getCylinderCapacity(): ?int
    {
        return $this->cylinderCapacity;
    }

    public function setCylinderCapacity(?int $cylinderCapacity): self
    {
        $this->cylinderCapacity = $cylinderCapacity;

        return $this;
    }

    public function getTurbo(): ?bool
    {
        return $this->turbo;
    }

    public function setTurbo(?bool $turbo): self
    {
        $this->turbo = $turbo;

        return $this;
    }

    public function getGeneration(): ?Generation
    {
        return $this->generation;
    }

    public function setGeneration(?Generation $generation): self
    {
        $this->generation = $generation;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getslug(): ?string
    {
        return $this->slug;
    }

    public function setslug(string $stringToslugify): self
    {
        $slug = new slugify();
        $this->slug  =  $slug->slugify($stringToslugify);
        return $this;
    }

    /**
     * @return Collection|Map[]
     */
    public function getMaps(): Collection
    {
        return $this->maps;
    }

    public function addMap(Map $map): self
    {
        if (!$this->maps->contains($map)) {
            $this->maps[] = $map;
            $map->setEngine($this);
        }

        return $this;
    }

    public function removeMap(Map $map): self
    {
        if ($this->maps->contains($map)) {
            $this->maps->removeElement($map);
            // set the owning side to null (unless already changed)
            if ($map->getEngine() === $this) {
                $map->setEngine(null);
            }
        }

        return $this;
    }
}