<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Find random books by category
     *
     * @param Category $category
     * @param int $limit
     * @return Book[]
     */
    public function findRandomBooksByCategory($category, $limit = 3)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.category = :category')
            ->setParameter('category', $category)
            ->orderBy('RAND()')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findByTitleOrAuthor($query)
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.author', 'a')
            ->where('b.title LIKE :query')
            ->orWhere('a.firstName LIKE :query')
            ->orWhere('a.lastName LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }
}