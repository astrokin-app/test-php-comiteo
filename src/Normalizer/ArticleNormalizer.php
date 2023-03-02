<?php

namespace App\Normalizer;

use App\Entity\Article;

class ArticleNormalizer
{
    public function normalize(Article $article): array
    {
        return [
            "title" => $article->getTitle(),
            "content" => $article->getContent(),
            "author" => $article->getAuthor(),
            "created_at" => $article->getCreatedAt()->format('c'),
            "updated_at" => $article->getUpdatedAt()->format('c'),
        ];
    }
}