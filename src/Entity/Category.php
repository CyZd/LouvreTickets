<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Tarif;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pricing;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $lowValue;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $highValue;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTarif(): ?string
    {
        return $this->Tarif;
    }

    public function setTarif(?string $Tarif): self
    {
        $this->Tarif = $Tarif;

        return $this;
    }

    public function getPricing(): ?float
    {
        return $this->pricing;
    }

    public function setPricing(?float $pricing): self
    {
        $this->pricing = $pricing;

        return $this;
    }

    public function getLowValue(): ?int
    {
        return $this->lowValue;
    }

    public function setLowValue(?int $lowValue): self
    {
        $this->lowValue = $lowValue;

        return $this;
    }

    public function getHighValue(): ?int
    {
        return $this->highValue;
    }

    public function setHighValue(?int $highValue): self
    {
        $this->highValue = $highValue;

        return $this;
    }
}
