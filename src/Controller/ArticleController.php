<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Entity\Filter\ArticleFilter;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ArticleController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/article/", name="article_list", methods={"GET"})
     * @SWG\Get(summary="List of articles")
     * @SWG\Response(
     *     response=200,
     *     description="Returns list of articles",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Article::class, groups={"Api"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="text",
     *     in="query",
     *     type="string",
     *     description="The field used to filter articles by include text"
     * )
     * @SWG\Parameter(
     *     name="categories",
     *     in="query",
     *     type="array",
     *     @SWG\Items(type="integer"),
     *     description="The field used to filter articles by categories. Set IDs of categories"
     * )
     * @SWG\Parameter(
     *     name="active",
     *     in="query",
     *     type="boolean",
     *     description="The field used to filter activiy of article"
     * )
     * @SWG\Tag(name="Article")
     * @param ArticleRepository $repository
     * @param ArticleFilter $filter
     * @return JsonResponse
     */
    public function list(ArticleRepository $repository, ArticleFilter $filter): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize(
                $repository->findByFilter($filter),
                'json',
                ['groups' => 'Api',]
            ),
            200, [], true);
    }

    /**
     * @Route("/api/article/{id}/", name="article_show", methods={"GET"})
     * @SWG\Get(summary="Article by ID")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the article",
     *     @Model(type=Article::class, groups={"Api"})
     * )
     * @SWG\Tag(name="Article")
     * @param Article $article
     * @return JsonResponse
     */
    public function show(Article $article): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize(
                $article,
                'json',
                ['groups' => 'Api',]
            ),
            200, [], true);
    }

    /**
     * @Route("/api/article/{id}/", name="article_delete", methods={"DELETE"})
     * @SWG\Delete(summary="Delete article")
     * @IsGranted("ROLE_ADMIN")
     * @SWG\Response(
     *     response=200,
     *     description="Delete article"
     * )
     * @SWG\Tag(name="Article")
     * @Security(name="basicAuth")
     * @param EntityManagerInterface $em
     * @param Article $article
     * @return JsonResponse
     */
    public function delete(EntityManagerInterface $em, Article $article): Response
    {
        $em->remove($article);
        $em->flush();
        return new Response();
    }

    /**
     * @Route("/api/article/", name="article_create", methods={"POST"})
     * @SWG\Post(summary="Create article")
     * @IsGranted("ROLE_ADMIN")
     * @SWG\Response(
     *     response=200,
     *     description="Create article",
     *     @Model(type=Article::class, groups={"Api"})
     * )
     * @SWG\Tag(name="Article")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     @Model(type=Article::class, groups={"Api"}),
     *     description="Article object that needs to be added"
     * )
     * @Security(name="basicAuth")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article('New Article');
        return $this->edit($request, $em, $article);
    }

    /**
     * @Route ("/api/article/{id}/", name="article_edit", methods={"PUT"})
     * @SWG\Put(summary="Update article")
     * @IsGranted("ROLE_ADMIN")
     * @SWG\Response(
     *     response=200,
     *     description="Create article",
     *     @Model(type=Article::class, groups={"Api"})
     * )
     * @SWG\Tag(name="Article")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     @Model(type=Article::class, groups={"Api"}),
     *     description="Article object that needs to be added"
     * )
     * @Security(name="basicAuth")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Article $article
     * @return JsonResponse
     */
    public function edit(Request $request, EntityManagerInterface $em, Article $article): JsonResponse
    {
        try {
            $this->serializer->deserialize(
                $request->getContent(),
                Article::class,
                'json',
                [
                    AbstractNormalizer::OBJECT_TO_POPULATE => $article,
                    AbstractNormalizer::IGNORED_ATTRIBUTES => ['id'],
                ]);
        } catch (NotNormalizableValueException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        $em->persist($article);
        $em->flush();
        return new JsonResponse(
            $this->serializer->serialize(
                $article,
                'json',
                ['groups' => 'Api',]
            ),
            200, [], true);
    }
}
