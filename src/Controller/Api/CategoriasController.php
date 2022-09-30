<?php

namespace App\Controller\Api;

use App\Entity\Categorias;
use App\Form\CategoriasFormType;
use App\Repository\CategoriasRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class CategoriasController extends AbstractFOSRestController
{
    private $catRepository;
    private $em;

    public function __construct(CategoriasRepository $categoriasRepository, EntityManagerInterface $em) {
        $this->catRepository = $categoriasRepository;
        $this->em = $em;
    }

    //http://127.0.0.1:8000/api/categorias
    /**
     * @Rest\Get(path="/categorias")
     * @Rest\View(serializerGroups={"categoria"}, serializerEnableMaxDepthChecks=true)
     * @return \App\Entity\Categorias[]
     */
    public function getCategorias() {
        $categorias = $this->catRepository->findAll();
        return $categorias;
    }

    //http://127.0.0.1:8000/api/add_categoria
    /**
     * @Rest\Post(path="/add_categoria")
     * @Rest\View(serializerGroups={"categoria"}, serializerEnableMaxDepthChecks=true)
     * @return Categorias
     * @throws \Doctrine\ORM\Exception\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function createCategoria(Request $request) {
        $categoria = new Categorias();

        $form = $this->createForm(CategoriasFormType::class, $categoria);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($categoria);
            $this->em->flush();
            return $categoria;
        }
        return $form;
    }
}

