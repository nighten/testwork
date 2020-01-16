<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Entity\Filter\CategoryFilter;
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

class CategoryController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/category/", name="category_list", methods={"GET"})
     * @SWG\Get(summary="List categories")
     * @SWG\Response(
     *     response=200,
     *     description="Returns list of article categories",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref=@Model(type=Category::class, groups={"Api"}))
     *     )
     * )
     * @SWG\Parameter(
     *     name="active",
     *     in="query",
     *     type="boolean",
     *     description="The field used to filter activiy of category"
     * )
     * @SWG\Tag(name="Category")
     * @param CategoryFilter $filter
     * @param CategoryRepository $repository
     * @return JsonResponse
     */
    public function list(CategoryFilter $filter, CategoryRepository $repository): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize(
                $repository->findByFilter($filter),
                'json',
                ['groups' => 'Api']
            ),
            200, [], true);
    }

    /**
     * @Route("/api/category/{id}/", name="category_show", methods={"GET"})
     * @SWG\Get(summary="Category by ID")
     * @SWG\Response(
     *     response=200,
     *     description="Returns the article category",
     *     @Model(type=Category::class, groups={"Api"})
     * )
     * @SWG\Tag(name="Category")
     * @param Category $category
     * @return JsonResponse
     */
    public function show(Category $category): JsonResponse
    {
        return new JsonResponse(
            $this->serializer->serialize(
                $category,
                'json',
                ['groups' => 'Api']),
            200, [], true);
    }

    /**
     * @Route("/api/category/{id}/", name="category_delete", methods={"DELETE"})
     * @SWG\Delete(summary="Delete category")
     * @IsGranted("ROLE_ADMIN")
     * @SWG\Response(
     *     response=200,
     *     description="Delete the article category"
     * )
     * @SWG\Tag(name="Category")
     * @Security(name="basicAuth")
     * @param EntityManagerInterface $em
     * @param Category $category
     * @return JsonResponse
     */
    public function delete(EntityManagerInterface $em, Category $category): Response
    {
        $em->remove($category);
        $em->flush();
        return new Response();
    }

    /**
     * @Route("/api/category/", name="category_create", methods={"POST"})
     * @SWG\Post(summary="Create category")
     * @IsGranted("ROLE_ADMIN")
     * @SWG\Response(
     *     response=200,
     *     description="Create the article category",
     *     @Model(type=Category::class, groups={"Api"})
     * )
     * @SWG\Tag(name="Category")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     @Model(type=Category::class, groups={"Api"}),
     *     description="Cartegory object that needs to be added"
     * )
     * @Security(name="basicAuth")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category('New Category');
        return $this->edit($request, $em, $category);
    }

    /**
     * @Route("/api/category/{id}/", name="category_edit", methods={"PUT"})
     * @SWG\Put(summary="Edit category")
     * @IsGranted("ROLE_ADMIN")
     * @SWG\Response(
     *     response=200,
     *     description="Edit the article category",
     *     @Model(type=Category::class, groups={"Api"})
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     @Model(type=Category::class, groups={"Api"}),
     *     description="Cartegory object that needs to be updated"
     * )
     * @SWG\Tag(name="Category")
     * @Security(name="basicAuth")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Category $category
     * @return JsonResponse
     */
    public function edit(Request $request, EntityManagerInterface $em, Category $category): JsonResponse
    {
        try {
            $this->serializer->deserialize(
                $request->getContent(),
                Category::class,
                'json',
                [
                    AbstractNormalizer::OBJECT_TO_POPULATE => $category,
                    AbstractNormalizer::IGNORED_ATTRIBUTES => ['id']
                ]);
        } catch (NotNormalizableValueException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        $em->persist($category);
        $em->flush();

        return new JsonResponse(
            $this->serializer->serialize(
                $category,
                'json',
                ['groups' => 'Api']),
            200, [], true);
    }
}
