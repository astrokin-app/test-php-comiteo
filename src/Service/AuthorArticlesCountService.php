<?php

namespace App\Service;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;

class AuthorArticlesCountService
{
    private $entityManager;
    private $cache;

    public function __construct(EntityManagerInterface $entityManager, CacheInterface $cache)
    {
        $this->entityManager = $entityManager;
        $this->cache = $cache;
    }

    public function countArticles(Author $author): int
    {
      $cacheKey = 'author_articles_count_' . $author->getName();
      $articlesCount = $this->cache->get($cacheKey, function () use ($author) {
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('COUNT(a.id)')
            ->from('App\Entity\Article', 'a')
            ->where('a.author = :author')
            ->setParameter('author', $author);

        return $qb->getQuery()->getSingleScalarResult();
      });

      return $articlesCount;
    }
}