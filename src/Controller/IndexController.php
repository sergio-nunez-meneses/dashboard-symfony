<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Products;
use App\Form\ProductReturnType;
use App\Form\ProductReservationType;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    private $repository;

    public function __construct(ProductsRepository $repository)
    {
        $this->repository = $repository;

    }

    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        $username = $this->getUser()->getUsername();
        $products = $this->repository->findAll();
        
        return $this->render('index/index.html.twig', [
            'current_page' => 'index',
            'current_username' => $username,
            'products' => $products
        ]);
    }
    
    /**
     * @Route("/reservation/{id}", name="reserve_product")
     */
    public function productReservation(Products $products, Request $request, $id)
    {
        $form = $this->createForm(ProductReservationType::class, $products);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // $product = new Products(); // for creating a new product
            $data = $form->getData(); // get all data from form inputs
            $product = $this->repository->find($id);
            $user = $this->getUser();
            $product->setIdUser($user);
            // $product->setName($data['name']);
            // $return_date = new \DateTime($data['reservationDate'] ' + 4 weeks');
            // $product->setReturnDate($return_date);

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('index');
        }

        return $this->render('reservation/index.html.twig', [
            'current_page' => 'reservation',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/return/{id}", name="return_product")
     */
    public function productReturn(Products $products, Request $request, $id)
    {
        $form = $this->createForm(ProductReturnType::class, $products);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $product = $this->getDoctrine()->getRepository(Products::class)->find($id);
            $user = $this->getUser();
            $product->setIdUser($user);
            // $reserve = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('index');
        }

        return $this->render('return/index.html.twig', [
            'current_page' => 'return',
            'form' => $form->createView()
        ]);
    }
}
