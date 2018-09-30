<?php

namespace App\Repository;

use App\Entity\CleanUser;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method CleanUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method CleanUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method CleanUser[]    findAll()
 * @method CleanUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CleanUserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CleanUser::class);
    }

//    /**
//     * @return CleanUser[] Returns an array of CleanUser objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CleanUser
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.useremail = :useremail')
            ->setParameter('useremail', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
