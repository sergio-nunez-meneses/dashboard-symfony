<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ProductsType;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProductsController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index(ProductsRepository $repo)
    {
        $product = $repo->findAll();

        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
            'product' => $product
        ]);
    }

        /**
     * @Route("/detail/{id}", name="product_detail")
     */
    public function detail(Products $product, Request $request, EntityManagerInterface $manager)
    {
        return $this->render('products/detail.html.twig', [
            'product' => $product
          ]);
    }

    /**
     * @Route("/new" , name="product_create")
     *@Route("/{id}/edit" , name="product_edit")
     */
    
    public function form(Products $product = null, Request $request,SluggerInterface $slugger, EntityManagerInterface $manager)
    {
      if (!$product) {
      $product = New Products();
    }

      $form = $this->createForm(ProductsType::class, $product);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {

        $receiptFile = $form->get('receipt')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($receiptFile) {
                $originalFilename = pathinfo($receiptFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$receiptFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $receiptFile->move(
                        $this->getParameter('receipt_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'receipt' property to store the PDF file name
                // instead of its contents
                $product->setReceipt($newFilename);
            }

            $manualFile = $form->get('manual')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($manualFile) {
                $originalFilename = pathinfo($manualFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$manualFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $manualFile->move(
                        $this->getParameter('manual_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'receipt' property to store the PDF file name
                // instead of its contents
                $product->setManual($newFilename);
            }    

        if (!$product->getId()) {
            $product->setAvailability(true);
        }

        $manager->persist($product);
        $manager->flush();

        return $this->redirectToRoute('product');

      }

      return $this->render('products/create.html.twig', [
        'formProduct'=> $form->createView(),
        'editMode' => $product->getId() !== null
      ]);
    }
    /**
 * @Route("/delete/{id}" , name="delete_product")
 */
public function delete($id)
{
    $entityManager = $this->getDoctrine()->getManager();
    $product = $entityManager->getRepository(Products::class)->find($id);

    if (!$product) {
        throw $this->createNotFoundException(
            'No product found for id '.$id
        );
    }
    
    $entityManager->remove($product);
    $entityManager->flush();

    return $this->redirectToRoute('product', [
    ]);
}
}
