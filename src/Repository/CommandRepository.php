<?php

namespace App\Repository;

use App\Entity\Command;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Command|null find($id, $lockMode = null, $lockVersion = null)
 * @method Command|null findOneBy(array $criteria, array $orderBy = null)
 * @method Command[]    findAll()
 * @method Command[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Command::class);
    }

    /**
    * @return Command[] Returns an array of Command objects
    */
    //remplace name par une recherche via l'id!!!
    public function findAllByCommandName($value)
    {
        $qb= $this->createQueryBuilder('c')
            ->andWhere('c.name = :name')
            ->setParameter('name', $value)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function removeCommand($id)
    {
        $qb=$this->createQueryBuilder('c')
            ->andWhere('c.Id = :Id')
            ->setParameter('Id', $id)
            ->getQuery();
        $this->remove($qb);
    }


    /*
    public function findOneBySomeField($value): ?Command
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
