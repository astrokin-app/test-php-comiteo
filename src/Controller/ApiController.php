<?php

namespace App\Controller;

use App\Entity\Article;
use App\Service\AuthorArticlesCountService;
use App\Normalizer\ArticleNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{

    private $articleNormalizer;

    public function __construct(ArticleNormalizer $articleNormalizer)
    {
        $this->articleNormalizer = $articleNormalizer;
    }

    /**
     * @Route("/api/v1/articles", name="api_v1_article_cget", methods={"GET"})
     */
    public function cget(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $articles = $em->getRepository('App:Article')->findAll();
        $normalizedArticles = [];

        foreach ($articles as $article) {
            $normalizedArticles[] = $this->articleNormalizer->normalize($article);
        }

        $response = $this->json($normalizedArticles);
        $response->setPublic();
        $response->setMaxAge(60 * 60 * 24);

        return $response;
    }

    /**
     * @Route("/api/v1/articles/{id}", name="api_v1_article_get", methods={"GET"})
     */
    public function getOne(int $id, AuthorArticlesCountService $countService): Response
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('App:Article')->find($id);

        if (!$article instanceof Article) {
            return $this->json([
                'error' => 'Article not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $normalizedArticle = $this->articleNormalizer->normalize($article, null, ['groups' => ['article', 'author']]);

        // Call the countArticles() method and add the result to the normalized article data
        $author = $article->getAuthor();
        $normalizedArticle['author_articles_count'] = $countService->countArticles($author);

        $response = $this->json($normalizedArticle);
        $response->setPublic();
        $response->setMaxAge(60 * 60 * 24);
        $response->setLastModified($article->getUpdatedAt());

        return $response;
    }

    /**
     * @Route("/api/v1/articles", name="api_v1_article_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            $normalizedArticle = $this->articleNormalizer->normalize($article);

            return $this->json($normalizedArticle);
        }

        return $this->json([
            'error' => 'Invalid data',
        ], Response::HTTP_BAD_REQUEST);
    }
}