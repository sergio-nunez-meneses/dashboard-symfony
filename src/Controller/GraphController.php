<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class GraphController extends AbstractController
{
    /**
     * @Route("admin/graph", name="graph")
     */
    public function index()
    {
        $data = new Product;
        $data->findQuantityByCategory();
        (dump($data));

        return $this->render('graph/index.html.twig', [
            [ 'data' => $data ],
            new Response('', 200, ['Content-Type' => 'image/svg+xml'])
        ]);
    }
}
