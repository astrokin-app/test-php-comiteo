<?php

namespace App\Normalizer;

use App\Entity\Author;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AuthorNormalizer implements NormalizerInterface
{
    public function normalize($author, $format = null, array $context = [])
    {
        return [
            'id' => $author->getId(),
            'name' => $author->getName(),
            'bio' => $author->getBio(),
            'created_at' => $author->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $author->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Author;
    }
}