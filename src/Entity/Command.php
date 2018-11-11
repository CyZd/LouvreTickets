<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Yasumi\Yasumi;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommandRepository")
 */
class Command
{
    public function __construct()
    {
        $this->Date=new \DateTime();
        $this->setDate(new \DateTime('now'));
        $this->ticketsOrdered = new ArrayCollection();
        $this->setName(uniqid(rand()));
        $this->setHasBeenPaid(false);
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Ticket", mappedBy="Command", cascade={"persist"})
     */
    private $ticketsOrdered;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $VisitorEmail;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DesiredDate;

    /**
     * @ORM\Column(type="smallint")
     */
    private $DayType;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasBeenPaid;

    private $TotalPrice;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getDesiredDate(): ?\DateTimeInterface
    {
        return $this->DesiredDate;
    }

    public function setDesiredDate(\DateTimeInterface $DesiredDate): self
    {
        $this->DesiredDate = $DesiredDate;

        return $this;
    }

    public function getDayType(): ?int
    {
        return $this->DayType;
    }

    public function setDayType(int $DayType): self
    {
        $this->DayType = $DayType;

        return $this;
    }

    public function getHasBeenPaid(): ?bool
    {
        return $this->hasBeenPaid;
    }

    public function setHasBeenPaid(bool $hasBeenPaid): self
    {
        $this->hasBeenPaid = $hasBeenPaid;

        return $this;
    }

    //added for phpunit testing
    public function setId(int $id): self
    {
        $this->id=$id;
        return $this;
    }

    /**
     * @return Collection|Ticket[]
     */
    public function getTicketsOrdered(): Collection
    {
        return $this->ticketsOrdered;
    }

    public function addTicketsOrdered(Ticket $ticketsOrdered): self
    {
        if (!$this->ticketsOrdered->contains($ticketsOrdered)) {
            $this->ticketsOrdered[] = $ticketsOrdered;
            $ticketsOrdered->setCommand($this);
        }

        return $this;
    }

    public function removeTicketsOrdered(Ticket $ticketsOrdered): self
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

    public function getVisitorEmail(): ?string
    {
        return $this->VisitorEmail;
    }

    public function setVisitorEmail(string $VisitorEmail): self
    {
        $this->VisitorEmail = $VisitorEmail;

        return $this;
    }

    public function setTotalPrice(float $price)
    {
        $this->TotalPrice=$price;
        return $this;
    }

    public function getTotalPrice()
    {
        return $this->TotalPrice;
    }

    //date check functions

    public function checkHalfDay(ExecutionContextInterface $context)
    {
        $givenDate=$this->getDayType();
        $orderDate=$this->getDesiredDate()->format('Y-m-d');
        $currentHour= date('H');

        if ($givenDate==1 && $currentHour>='14' && $orderDate==(new \DateTime('NOW'))->format('Y-m-d')) {
            $context->buildViolation('Vous ne pouvez pas commander de billet pleine journée après 14h.')
                ->atPath('DayType')
                ->addViolation();
        }
    }

    public function isSunday(ExecutionContextInterface $context)
    {
        $orderDate=$this->getDesiredDate()->format('D');
        if ($orderDate=='Sun') {
            $context->buildViolation('Vous ne pouvez pas commander de billet pour le dimanche.')
                ->atPath('DesiredDate')
                ->addViolation();
        }
    }

    public function isFeastDay(ExecutionContextInterface $context)
    {
        $desiredDate=$this->getDesiredDate()->format('Y-m-d');
        $yearOfVisit=$this->getDesiredDate()->format('Y');
        $feastDays= Yasumi::create('France', $yearOfVisit);
        $feastDaysOfYear=$feastDays->between(new \DateTime('01-01-'.$yearOfVisit), new \DateTime('31-12-'.$yearOfVisit));

        foreach ($feastDaysOfYear as $day) {
            if ($desiredDate == $day) {
                $context->buildViolation('La date que vous avez choisi est un jour férié.')
                ->atPath('DesiredDate')
                ->addViolation();
            }
        }
    }

}
