<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Users;
use App\Entity\Products;
use App\Form\ProductReturnType;
use App\Form\ProductReservationType;
use App\Form\ProductReservation1Type;
use App\Repository\UsersRepository;
use App\Repository\ProductsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private $users_repository;
    private $products_repository;

    public function __construct(UsersRepository $users_repository, ProductsRepository $products_repository)
    {
        // short for $this->getDoctrine()->getRepository(Entity::class);
        $this->users_repository = $users_repository;
        $this->products_repository = $products_repository;

    }

    /**
     * @Route("/", name="dashboard")
     */
    public function dashboard()
    {
        return $this->render('Dashboard/index.html.twig', [
            'current_page' => 'dashboard'
        ]);
    }

    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $name = $user->getUsername();
        $role = $this->users_repository->findOneBy(['username' => $name])->getRoles()[0];
        $products = $this->products_repository->findAll();

        if ($role === 'ROLE_ADMIN')
        {
            return $this->render('admin/products.html.twig', [
                'current_page' => 'admin',
                'current_username' => $name,
                'products' => $products
            ]);
        } elseif ($role === 'ROLE_USER')
        {
            return $this->render('index/index.html.twig', [
                'current_page' => 'index',
                'current_username' => $name,
                'products' => $products
            ]);
        }
    }

    /**
     * @Route("/productFront/{id}", name="detail_product")
     */
    public function detail(Products $product): Response
    {
        $username = $this->getUser()->getUsername();

        return $this->render('index/productFront.html.twig', [
            'current_page' => 'product',
            'product' => $product

      ]);
    }

    /**
     * @Route("/reservation/{id}", name="reserve_product")
     */
    public function productReservation(Products $products, Request $request, $id)
    {
        $form = $this->createForm(ProductReservationType::class, $products);
        $form1 = $this->createForm(ProductReservation1Type::class, $products);
        $form->handleRequest($request);
        $form1->handleRequest($request);

        $return_date = $this->products_repository->find($id)->getReturnDate();
        $availability = $this->products_repository->find($id)->getAvailability();

        if ($form->isSubmitted() && $form->isValid())
        {
            $product = $this->products_repository->find($id);
            $user = $this->getUser();
            $product->setIdUser($user);

            $reservation_date = $form->getData()->getReservationDate();
            $product->setReservationDate($reservation_date);
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $return_date = $reservation_date;
            $return_date = $return_date->add(new \DateInterval('P30D'));
            $product->setReturnDate($return_date);
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('index');
        }


        if ($availability == 1) {
            return $this->render('reservation/index.html.twig', [
            'current_page' => 'reservation',
            'form' => $form->createView()
            ]);
        } else {
            return $this->render('reservation/index1.html.twig', [
            'current_page' => 'reservation',
            'availability' => $availability,
            'return_date' => $return_date,
            'form' => $form1->createView()
            ]);
        }
        
        // return $this->render('reservation/index.html.twig', [
        //     'current_page' => 'reservation',
        //     'availability' => $availability,
        //     'return_date' => $return_date,
        //     'form' => $form->createView()
        //     ]);

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
            $product = $this->products_repository->find($id);
            $user = $this->getUser();
            $product->setIdUser($user);

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
