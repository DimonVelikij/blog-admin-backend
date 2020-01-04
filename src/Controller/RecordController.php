<?php

namespace App\Controller;

use App\Entity\Record;
use App\Service\FormService;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     */
    public function list()
    {
        return $this->json([
            'message' => 'List',
            'path' => 'src/Controller/RecordController.php',
        ]);
    }

    /**
     * @Route("/{id}", name="item", requirements={"id"="\d+"}, methods={"GET"})
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function item(int $id)
    {
        return $this->json([
            'message'   =>  'Item',
            'path' => 'src/Controller/RecordController.php',
        ]);
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
     */
    public function update(Request $request, int $id)
    {
        return $this->json([
            'message'   =>  'Update',
            'path' => 'src/Controller/RecordController.php',
        ]);
    }

    /**
     * @Route("", name="delete_all", methods={"DELETE"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteAll()
    {
        return $this->json([
            'message'   =>  'DeleteAll',
            'path' => 'src/Controller/RecordController.php',
        ]);
    }

    /**
     * @Route("/{id}", name="delete", requirements={"id"="\d+"}, methods={"DELETE"})
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete(int $id)
    {
        return $this->json([
            'message'   =>  'Delete',
            'path' => 'src/Controller/RecordController.php',
        ]);
    }
}
