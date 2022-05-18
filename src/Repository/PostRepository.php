<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{

    public const PAGINATOR_PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Post $entity, bool $flush = false): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Post $entity, bool $flush = false): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * Retourne une liste d'article suivant la pagination
     *
     * @param integer $offset
     * @return Paginator
     */
    public function postPaginator (int $offset): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->setFirstResult($offset)
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->getQuery()
        ;

        return new Paginator($qb);
    }

    /**
     * Retourne les articles organisés
     *
     * @param string $sort
     * @param string $direction
     * @return array
     */
    public function knpPaginator (string $sort= 'id', string $direction = 'asc'): array
    {
        $qb = $this->createQueryBuilder('p');
        if ($sort === "category.name") {
            $qb->join('p.category', 'c')
            ->orderBy('c.name', $direction);
        } else {
            $qb->orderBy('p.'.$sort, $direction);
        }
        
        return $qb->getQuery()
        ->getResult()
        ;
    }

    public function search (string $keyword = ""): array
    {
        $qb = $this->createQueryBuilder('p');

            $qb->join('p.category', 'c')
            ->where($qb->expr()->like('p.title', $qb->expr()->literal("% $keyword%")))
            ->orWhere($qb->expr()->like('p.content', $qb->expr()->literal("% $keyword%")))
            ->orWhere($qb->expr()->like('c.name', $qb->expr()->literal("% $keyword%")))
            ;
        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
