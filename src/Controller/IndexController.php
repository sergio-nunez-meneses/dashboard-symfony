<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductReservationType;
use App\Repository\ProductsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    private $repository;

    public function __construct(ProductsRepository $repository, ManagerRegistry $registry)
    {
        $this->repository = $repository;
        $this->registry = $registry;
    }

    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        $products = $this->repository->findAll();

        return $this->render('index/index.html.twig', [
            'current_page' => 'index',
            'products' => $products,
        ]);
    }
    
    /**
     * @Route("/reservation/{id}", name="reserve")
     */
    public function productReservation(Products $products, Request $request)
    {
        $form = $this->createForm(ProductReservationType::class, $products);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $reserve = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($products);
            $entityManager->flush();
            return $this->redirectToRoute('index');
        }

        return $this->render('reservation/index.html.twig', [
            'current_page' => 'reservation',
            'form' => $form->createView()
        ]);
    }
}
