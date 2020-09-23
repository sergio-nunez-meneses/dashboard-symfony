<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsType;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    /**
     * @Route("/products", name="products")
     */
    public function index(ProductsRepository $repo)
    {
        $products = $repo->findAll();

        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
            'products' => $products
        ]);
    }

    /**
     * @Route("/new" , name="products_create")
     *@Route("/{id}/edit" , name="products_edit")
     */
    
    public function form(Products $products = null, Request $request, EntityManagerInterface $manager)
    {
      if (!$products) {
      $products = New Products();
    }

      $form = $this->createForm(ProductsType::class, $products);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {

        if (!$products->getId()) {
            $products->setAvailability(true);
        }

        $manager->persist($products);
        $manager->flush();

        return $this->redirectToRoute('products');

      }

      return $this->render('products/create.html.twig', [
        'formProducts'=> $form->createView(),
        'editMode' => $products->getId() !== null
      ]);
    }

}
