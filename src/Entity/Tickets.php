<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Yasumi\Yasumi;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketsRepository")
 */
class Tickets
{
    public function __construct()
    {
        $this->Date=new \DateTime();
        $this->setDate(new \DateTime('now'));
    }
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date;

    /**
     * @ORM\Column(type="smallint")
     */
    private $PriceTag;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $VisitorName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $VisitorSurName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $VisitorCountry;

    /**
     * @ORM\Column(type="datetime")
     */
    private $VisitorDoB;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reducedPrice;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Command", inversedBy="ticketsOrdered")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Command;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPriceTag(): ?string
    {
        return $this->PriceTag;
    }

    public function setPriceTag(float $PriceTag): self
    {
        $this->PriceTag = $PriceTag;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getVisitorName(): ?string
    {
        return $this->VisitorName;
    }

    public function setVisitorName(string $VisitorName): self
    {
        $this->VisitorName = $VisitorName;

        return $this;
    }

    public function getVisitorSurName(): ?string
    {
        return $this->VisitorSurName;
    }

    public function setVisitorSurName(string $VisitorSurName): self
    {
        $this->VisitorSurName = $VisitorSurName;

        return $this;
    }

    public function getVisitorCountry(): ?string
    {
        return $this->VisitorCountry;
    }

    public function setVisitorCountry(string $VisitorCountry): self
    {
        $this->VisitorCountry = $VisitorCountry;

        return $this;
    }

    public function getVisitorDoB(): ?\DateTimeInterface
    {
        return $this->VisitorDoB;
    }

    public function setVisitorDoB(\DateTimeInterface $VisitorDoB): self
    {
        $this->VisitorDoB = $VisitorDoB;

        return $this;
    }

    public function getReducedPrice(): ?bool
    {
        return $this->reducedPrice;
    }

    public function setReducedPrice(bool $reducedPrice): self
    {
        $this->reducedPrice = $reducedPrice;

        return $this;
    }

    public function getCommand(): ?Command
    {
        return $this->Command;
    }

    public function setCommand(?Command $Command): self
    {
        $this->Command = $Command;

        return $this;
    }
}
