<?php

namespace App\Controller;

use App\Entity\Record;
use App\Repository\RecordRepository;
use App\Service\FormService;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

/**
 * @Route("/records", name="records_")
 * Class RecordController
 * @package App\Controller
 */
class RecordController extends AbstractController
{
    /** @var EntityManagerInterface  */
    private $entityManager;

    /** @var SerializerService  */
    private $serializer;

    /** @var FormService */
    private $formService;

    /**
     * RecordController constructor.
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
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function list()
    {
        $records = $this->entityManager->getRepository(Record::class)->findAll();

        return $this->json($this->serializer->normalize($records));
    }

    /**
     * @Route("/{id}", name="item", requirements={"id"="\d+"}, methods={"GET"})
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function item(int $id)
    {
        $record = $this->entityManager->getRepository(Record::class)->find($id);

        if (!$record) {
            return $this->json('Нет заметки с id ' . $id, Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->serializer->normalize($record));
    }

    /**
     * @Route("", name="create", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function create(Request $request)
    {
        /** @var Record $record */
        $record = $this->serializer->deserialize($request->getContent(), Record::class, 'json');

        if (!$this->formService->isValid($record)) {
            return $this->json(['errors' => $this->formService->getErrors()], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($record);
        $this->entityManager->flush();

        return $this->json(['id' => $record->getId()], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}", name="update", requirements={"id"="\d+"}, methods={"PUT"})
     * @param Request $request
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function update(Request $request, int $id)
    {
        $record = $this->entityManager->getRepository(Record::class)->find($id);

        if (!$record) {
            return $this->json('Нет заметки с id ' . $id, Response::HTTP_NOT_FOUND);
        }

        $record = $this->serializer->deserialize($request->getContent(), Record::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $record]);

        if (!$this->formService->isValid($record)) {
            return $this->json(['errors' => $this->formService->getErrors()], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($record);
        $this->entityManager->flush();

        return $this->json(['id' => $record->getId()]);
    }

    /**
     * @Route("", name="delete_all", methods={"DELETE"})
     * @return Response
     */
    public function deleteAll()
    {
        /** @var RecordRepository $recordRepository */
        $recordRepository = $this->entityManager->getRepository(Record::class);

        $recordRepository->createQueryBuilder('record')->delete()->getQuery()->getResult();

        return new Response();
    }

    /**
     * @Route("/{id}", name="delete", requirements={"id"="\d+"}, methods={"DELETE"})
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(int $id)
    {
        $record = $this->entityManager->getRepository(Record::class)->find($id);

        if (!$record) {
            return $this->json('Нет заметки с id ' . $id, Response::HTTP_NOT_FOUND);
        }

        $recordId = $record->getId();
        $this->entityManager->remove($record);
        $this->entityManager->flush();

        return $this->json(['id' => $recordId]);
    }
}
