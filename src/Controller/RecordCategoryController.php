<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/record-categories", name="record_categories_")
 * Class RecordCategoryController
 * @package App\Controller
 */
class RecordCategoryController extends AbstractController
{
    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function list()
    {
        return $this->json([
            'message' => 'List record categories',
            'path' => 'src/Controller/RecordCategoryController.php',
        ]);
    }

    /**
     * @Route("/{id}", name="item", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function item()
    {
        return $this->json([
            'message' => 'Get single record category',
            'path' => 'src/Controller/RecordCategoryController.php',
        ]);
    }

    /**
     * @Route("", name="create", methods={"POST"})
     */
    public function create()
    {
        return $this->json([
            'message' => 'Create record category',
            'path' => 'src/Controller/RecordCategoryController.php',
        ]);
    }

    /**
     * @Route("/{id}", name="update", requirements={"id"="\d+"}, methods={"PUT"})
     */
    public function update()
    {
        return $this->json([
            'message' => 'Update record category',
            'path' => 'src/Controller/RecordCategoryController.php',
        ]);
    }

    /**
     * @Route("/{id}", name="delete", requirements={"id"="\d+"}, methods={"DELETE"})
     */
    public function delete()
    {
        return $this->json([
            'message' => 'Delete record category',
            'path' => 'src/Controller/RecordCategoryController.php',
        ]);
    }
}
