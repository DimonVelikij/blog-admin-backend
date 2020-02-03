<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\FormService;
use App\Service\SerializerService;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("/categories", name="categories_")
 * Class CategoryController
 * @package App\Controller
 */
class CategoryController extends AbstractController
{
    /** @var EntityManagerInterface  */
    private $entityManager;

    /** @var SerializerService  */
    private $serializer;

    /** @var FormService */
    private $formService;

    /**
     * CategoryController constructor.
     * @param EntityManagerInterface $entityManager
     * @param SerializerService $serializerService
     * @param FormService $formService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerService $serializerService,
        FormService $formService
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializerService;
        $this->formService = $formService;
    }

    /**
     * @Route("", name="list", methods={"GET"})
     * @return JsonResponse
     * @throws AnnotationException
     * @throws ExceptionInterface
     */
    public function list()
    {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->json($this->serializer->normalize($categories));
    }

    /**
     * @Route("/{id}", name="item", requirements={"id"="\d+"}, methods={"GET"})
     * @param int $id
     * @return JsonResponse
     * @throws AnnotationException
     * @throws ExceptionInterface
     */
    public function item(int $id)
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            return $this->json('Нет категории с id ' . $id, Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->serializer->normalize($category));
    }

    /**
     * @Route("", name="create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws AnnotationException
     */
    public function create(Request $request)
    {
        /** @var Category $category */
        $category = $this->serializer->deserialize($request->getContent(), Category::class, 'json');

        if (!$this->formService->isValid($category)) {
            return $this->json(['errors' => $this->formService->getErrors()], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $this->json(['id' => $category->getId()], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="update", requirements={"id"="\d+"}, methods={"PUT"})
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws AnnotationException
     */
    public function update(Request $request, int $id)
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            return $this->json('Нет категории с id ' . $id, Response::HTTP_NOT_FOUND);
        }

        $category = $this->serializer->deserialize($request->getContent(), Category::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $category]);

        if (!$this->formService->isValid($category)) {
            return $this->json(['errors' => $this->formService->getErrors()], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $this->json(['id' => $category->getId()]);
    }

    /**
     * @Route("", name="delete_all", methods={"DELETE"})
     * @return Response
     */
    public function deleteAll()
    {
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $this->entityManager->getRepository(Category::class);

        $categoryRepository->createQueryBuilder('record_category')->delete()->getQuery()->getResult();

        return new Response();
    }

    /**
     * @Route("/{id}", name="delete", requirements={"id"="\d+"}, methods={"DELETE"})
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id)
    {
        $category = $this->entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            return $this->json('Нет категории с id ' . $id, Response::HTTP_NOT_FOUND);
        }

        $categoryId = $category->getId();
        $this->entityManager->remove($category);
        $this->entityManager->flush();

        return $this->json(['id' => $categoryId]);
    }
}
