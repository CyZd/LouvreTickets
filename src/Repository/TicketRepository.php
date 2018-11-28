<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tickets|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tickets|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tickets[]    findAll()
 * @method Tickets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    /**
     * @return Ticket[] Returns an array of Tickets objects
     */
    public function findAllForOneDate(\DateTimeInterface $desiredDate)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.DesiredDate = :DesiredDate')
            ->setParameter('DesiredDate', $desiredDate)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAssociatedOrder($id)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.commandId = :Id')
            ->setParameter('Id', $id)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function removeTicket($id)
    {
        $qb=$this->createQueryBuilder('t')
            ->andWhere('t.commandId = :Id')
            ->setParameter('Id', $id)
            ->getQuery();
        $this->remove($qb);
    }

    /*
    public function findOneBySomeField($value): ?Tickets
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
