<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProductsRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class GraphController extends AbstractController
{
    private $products_repository;

    public function __construct(ProductsRepository $products_repository)
    {
        // short for $this->getDoctrine()->getRepository(Entity::class);
        $this->products_repository = $products_repository;

    }


    /**
     * @Route("admin/graph", name="graph")
     */
    public function index()
    {
        $data = $this->products_repository->findQuantityByCategory();
        $categories = $number = [];

        foreach ($data as $key => $value)
        {
          $categories[] = '"' . $data[$key]['category'] . '"';
          $number[] = '"' . $data[$key][1] . '"';
        }

        return $this->render('graph/index.html.twig', [
            'current_page' => 'graph',
            'categories' => $categories,
            'total' => $number
        ]);
    }
}
