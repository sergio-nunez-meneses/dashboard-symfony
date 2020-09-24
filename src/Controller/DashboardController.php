<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Products;
use App\Form\ProductFormType;
use App\Repository\ProductsRepository;


class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index()
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    /**
     * @Route("/products", name="products")
     */

    public function AllProducts(ProductsRepository $repo)
    {
      $products = $repo->findAll();
      return $this->render('dashboard/products.html.twig', [
          'products' => $products
      ]);
    }

    /**
     * @Route("/products/new", name="product_create")
     * @Route("/products/{id}/edit", name= "product_edit")
     */

    public function form (Products $product = null, Request $request, EntityManagerInterface $manager, SluggerInterface $slugger){
      if(!$product){
        $product = new Products;
      }

      $formProduct = $this->createForm(ProductFormType::class, $product);
      $formProduct->HandleRequest($request);

      if($formProduct->isSubmitted() && $formProduct->isValid()){
        $receiptFile = $formProduct->get('receipt')->getData();
        $manualFile = $formProduct->get('manual')->getData();

        if ($receiptFile) {
               $originalFilename = pathinfo($receiptFile->getClientOriginalName(), PATHINFO_FILENAME);
               $safeFilename = $slugger->slug($originalFilename);
               $newReceiptFilename = $safeFilename.'-'.uniqid().'.'.$receiptFile->guessExtension();
               try {
                   $receiptFile->move(
                       $this->getParameter('receipt_directory'),
                       $newReceiptFilename
                   );
               } catch (FileException $e) {
                   // ... handle exception if something happens during file upload
               }
        }
        if ($manualFile) {
              $originalFilename = pathinfo($manualFile->getClientOriginalName(), PATHINFO_FILENAME);
              $safeFilename = $slugger->slug($originalFilename);
              $newManualFilename = $safeFilename.'-'.uniqid().'.'.$manualFile->guessExtension();
              try {
                  $manualFile->move(
                      $this->getParameter('manual_directory'),
                      $newManualFilename
                  );
              } catch (FileException $e) {
                  // ... handle exception if something happens during file upload
              }

               // updates the 'brochureFilename' property to store the PDF file name
               // instead of its contents
               $product->setReceipt($newReceiptFilename);
               $product->setManual($newManualFilename);
               }


        $manager->persist($product);
        $manager->flush();
        return $this->redirectToRoute('product_detail', ['id' => $product->getId()]);
      }

      return $this->render('dashboard/create.html.twig',[
        'formProduct' => $formProduct->createView(),
        'editMode' => $product->getId() !== null,
        'name' => $product->getName($product->getId())
      ]);
    }

    /**
     * @Route("/products/{id}/delete", name="product_delete", methods="DELETE|GET")
    */
    
    public function DeleteProduct(Products $product, Request $request, EntityManagerInterface $manager): Response
    {
      
      if (true || $this->isCsrfTokenValid('delete' . $product->getId(), $request->get('_token')))
      {
        
        $manager->remove($product);
        $manager->flush();
        // return new Response('Product deleted');
      }

      return $this->redirectToRoute('products'); 

    }

    /**
     * @Route("/products/{id}", name="product_detail")
     */

    public function DetailProduct(Products $product)
    {
      // $now = new DateTime();
      // $interval = $now->diff($product->getWarrantyDate());

      return $this->render('dashboard/product.html.twig', [
          'product' => $product
          // 'interval' => $interval
      ]);
    }


}
