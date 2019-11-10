<?php
namespace App\Repository;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Common\Collections\Collection;
/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
  public function __construct(RegistryInterface $registry)
  {
      parent::__construct(
          $registry,
          Post::class
      );
  }
    /**
     * @return Post[] Returns an array of Post objects
    */
    public function findAllByUsers(Collection $users)
    {
        // dump($users);die;
        $result = $this->createQueryBuilder('p')
            ->select('p')
            ->where( 'p.user IN (:following) ')
            ->setParameter('following', $users)
            ->orderBy('p.time', 'DESC')
            ->getQuery()
            ->getResult()
        ;
        return $result;
    }
    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}