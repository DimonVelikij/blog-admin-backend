<?php

namespace App\Controller;

use App\Entity\RecordCategory;
use App\Repository\RecordCategoryRepository;
use App\Service\FormService;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("/record-categories", name="record_categories_")
 * Class RecordCategoryController
 * @package App\Controller
 */
class RecordCategoryController extends AbstractController
{
    /** @var SerializerService  */
    private $serializer;

    /** @var FormService */
    private $formService;

    /** @var EntityManagerInterface  */
    private $entityManager;

    /**
     * RecordCategoryController constructor.
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
        $this->serializer = $serializerService->serializer;
        $this->formService = $formService;
    }

    /**
     * @Route("", name="list", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function list()
    {
        /** @var RecordCategory[] $recordCategories */
        $recordCategories = $this->entityManager->getRepository(RecordCategory::class)->findAll();

        return $this->json($this->serializer->normalize($recordCategories, null, $this->getNormalizeContext()));
    }

    /**
     * @Route("/{id}", name="item", requirements={"id"="\d+"}, methods={"GET"})
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function item(int $id)
    {
        /** @var RecordCategory $recordCategory */
        $recordCategory = $this->entityManager->getRepository(RecordCategory::class)->find($id);

        if (!$recordCategory) {
            return $this->json('Нет категории с id ' . $id, Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->serializer->normalize($recordCategory, null, $this->getNormalizeContext()));
    }

    /**
     * @Route("", name="create", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(Request $request)
    {
        /** @var RecordCategory $recordCategory */
        $recordCategory = $this->serializer->deserialize($request->getContent(), RecordCategory::class, 'json');

        if (!$this->formService->isValid($recordCategory)) {
            return $this->json(['errors' => $this->formService->getErrors()], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($recordCategory);
        $this->entityManager->flush();

        return $this->json(['id' => $recordCategory->getId()], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="update", requirements={"id"="\d+"}, methods={"PUT"})
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(Request $request, int $id)
    {
        /** @var RecordCategory $recordCategory */
        $recordCategory = $this->entityManager->getRepository(RecordCategory::class)->find($id);

        if (!$recordCategory) {
            return $this->json('Нет категории с id ' . $id, Response::HTTP_NOT_FOUND);
        }

        $recordCategory = $this->serializer->deserialize($request->getContent(), RecordCategory::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $recordCategory]);

        if (!$this->formService->isValid($recordCategory)) {
            return $this->json(['errors' => $this->formService->getErrors()], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($recordCategory);
        $this->entityManager->flush();

        return $this->json(['id' => $recordCategory->getId()]);
    }

    /**
     * @Route("", name="delete_all", methods={"DELETE"})
     */
    public function deleteAll()
    {
        /** @var RecordCategoryRepository $recordCategoryRepository */
        $recordCategoryRepository = $this->entityManager->getRepository(RecordCategory::class);

        $recordCategoryRepository->createQueryBuilder('record_category')->delete();

        return new Response();
    }

    /**
     * @Route("/{id}", name="delete", requirements={"id"="\d+"}, methods={"DELETE"})
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(int $id)
    {
        /** @var RecordCategory $recordCategory */
        $recordCategory = $this->entityManager->getRepository(RecordCategory::class)->find($id);

        if (!$recordCategory) {
            return $this->json('Нет категории с id ' . $id, Response::HTTP_NOT_FOUND);
        }

        $recordCategoryId = $recordCategory->getId();
        $this->entityManager->remove($recordCategory);
        $this->entityManager->flush();

        return $this->json(['id' => $recordCategoryId]);
    }

    /**
     * @return array
     */
    private function getNormalizeContext(): array
    {
        return ['groups' => ['admin']];
    }
}
