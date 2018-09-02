<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandRepository")
 */
class Command
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
     * @ORM\OneToMany(targetEntity="App\Entity\Tickets", mappedBy="Command")
     */
    private $ticketsOrdered;

    public function __construct()
    {
        $this->ticketsOrdered = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setname(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Tickets[]
     */
    public function getTicketsOrdered(): Collection
    {
        return $this->ticketsOrdered;
    }

    public function addTicketsOrdered(Tickets $ticketsOrdered): self
    {
        if (!$this->ticketsOrdered->contains($ticketsOrdered)) {
            $this->ticketsOrdered[] = $ticketsOrdered;
            $ticketsOrdered->setCommand($this);
        }

        return $this;
    }

    public function removeTicketsOrdered(Tickets $ticketsOrdered): self
    {
        if ($this->ticketsOrdered->contains($ticketsOrdered)) {
            $this->ticketsOrdered->removeElement($ticketsOrdered);
            // set the owning side to null (unless already changed)
            if ($ticketsOrdered->getCommand() === $this) {
                $ticketsOrdered->setCommand(null);
            }
        }

        return $this;
    }
}
