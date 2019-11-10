<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Undocumented function
     *
     * @param void
     * @return User[]
     */
    public function findAllWithMoreThan5Posts()
    {
        return $this->getFindMoreThan5PostsQuery()
            ->getQuery()
            ->getResult();
    }  

    /**
     *
     * @param User $user
     * @return User[]
     */
    public function findAllWithMoreThan5PostsExceptUser(User $user)
    {
        return $this->getFindMoreThan5PostsQuery()
            ->andHaving('u != :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }  

    public function getFindMoreThan5PostsQuery(): \Doctrine\ORM\QueryBuilder
    {
        return $qb = $this->createQueryBuilder('u')
            ->select('u')
            ->innerJoin('u.posts', 'mp')
            ->groupBy('u')
            ->having('count(mp) > 5');
    }
}
