<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations\Get;

class ApiController extends FOSRestController
{
    /**
     * @Get("/api/v1/articles", name="api_v1_article_cget")
     */
    public function cgetAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('AppBundle:Article')->findAll();

        $normalizedArticles = [];

        foreach ($articles as $article) {
            $normalizedArticle = [
                "title"      => $article->getTitle(),
                "content"    => $article->getContent(),
                "author"     => $article->getAuthor(),
                "created_at" => $article->getCreatedAt()->format('c'),
                "updated_at" => $article->getUpdatedAt()->format('c'),
                "uri"        => $this->generateUrl('api_v1_article_get', ["id" => $article->getId()])
            ];

            $normalizedArticles[] = $normalizedArticle;
        }

        return new JsonResponse($normalizedArticles);
    }

    /**
     * @Get("/api/v1/articles/{id}", name="api_v1_article_get")
     */
    public function getAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $article = $em->getRepository('AppBundle:Article')->find($id);

        $normalizedArticle = [
            "title"      => $article->getTitle(),
            "content"    => $article->getContent(),
            "author"     => $article->getAuthor(),
            "created_at" => $article->getCreatedAt()->format('c'),
            "updated_at" => $article->getUpdatedAt()->format('c'),
            "uri"        => $this->generateUrl('api_v1_article_get', ["id" => $article->getId()])
       ];

        return new JsonResponse($normalizedArticle);
    }
}
