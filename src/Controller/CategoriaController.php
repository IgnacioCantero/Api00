<?php

namespace App\Controller;

use App\Entity\Categorias;
use App\Repository\CategoriasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoriaController extends AbstractController
{
    private $catRepository;

    public function __construct(CategoriasRepository $catRepository) {
        $this->catRepository = $catRepository;
    }

    //http://127.0.0.1:8000/categoria?categoria=
    /**
     * @Route("/categoria", name="create_categoria")
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function createController(EntityManagerInterface $entityManager, Request $request) {
        $categoriaNombre = $request->get("categoria");
        if (!$categoriaNombre) {
            $response = new JsonResponse();
            $response->setData([
                'succes' => false,
                'data' => null
            ]);
            return $response;
        }

        $categoria = new Categorias();
        $categoria->setCategoria($categoriaNombre);

        $entityManager->persist($categoria);
        $entityManager->flush();

        $response = new JsonResponse();
        $response->setData([
            'succes' => true,
            'data' => [
                [
                    'id' => $categoria->getId(),
                    'categoria' => $categoria->getCategoria()
                ]
            ]
        ]);
        $response->setStatusCode(200);
        return $response;
    }

    //http://127.0.0.1:8000/categoria/list
    /**
     * @Route("/categoria/list", name="categoria_list")
     * @return JsonResponse
     */
    public function listCategoria() {
        $categorias = $this->catRepository->findAll();
        $categoriasArray = [];
        foreach ($categorias as $cat) {
            $categoriasArray[] = [
                'id' => $cat->getId(),
                'categoria' => $cat->getCategoria()
            ];
        }

        $response = new JsonResponse();
        $response->setData([
            'succes' => true,
            'data' => $categoriasArray
        ]);
        $response->setStatusCode(200);
        return $response;
    }
}
