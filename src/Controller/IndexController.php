<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Users;
use App\Entity\Products;
use App\Form\ProductReturnType;
use App\Form\ProductReservationType;
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
        // short for $this->getDoctrine()->getRepository(Entity:class);
        $this->users_repository = $users_repository;
        $this->products_repository = $products_repository;

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
            return $this->render('admin/index.html.twig', [
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
     * @Route("/reservation/{id}", name="reserve_product")
     */
    public function productReservation(Products $products, Request $request, $id)
    {
        $form = $this->createForm(ProductReservationType::class, $products);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $product = $this->products_repository->find($id);
            $user = $this->getUser();
            $product->setIdUser($user);

            // $real_reservation_date = $form->getData()->getReservationDate();

            $reservation_date = $form->getData()->getReservationDate();
            $product->setReservationDate($reservation_date);
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);

            $return_date = $reservation_date->add(new \DateInterval('P30D'));
            $product->setReturnDate($return_date);
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
            // $product = $this->getDoctrine()->getRepository(Products::class)->find($id);
            $product = $this->products_repository->find($id);
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
