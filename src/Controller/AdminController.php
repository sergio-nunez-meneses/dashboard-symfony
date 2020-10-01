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


class AdminController extends AbstractController
{
    
    /**
     * @Route("/admin/products", name="admin_products")
     */

    public function AllProducts(ProductsRepository $repo)
    {
      $products = $repo->findAll();
      return $this->render('admin/products.html.twig', [
          'products' => $products
      ]);
    }

    /**
     * @Route("admin/products/new", name="admin_product_create")
     * @Route("admin/products/{id}/edit", name= "admin_product_edit")
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
        return $this->redirectToRoute('admin_product_detail', ['id' => $product->getId()]);
      }

      return $this->render('admin/create.html.twig',[
        'formProduct' => $formProduct->createView(),
        'editMode' => $product->getId() !== null,
        'name' => $product->getName($product->getId())
      ]);
    }

    /**
     * @Route("admin/products/{id}/delete", name="admin_product_delete", methods="DELETE|GET")
    */
    
    public function DeleteProduct(Products $product, Request $request, EntityManagerInterface $manager): Response
    {
      
      if (true || $this->isCsrfTokenValid('delete' . $product->getId(), $request->get('_token')))
      {
        
        $manager->remove($product);
        $manager->flush();
        // return new Response('Product deleted');
      }

      return $this->redirectToRoute('admin_products'); 

    }

    /**
     * @Route("admin/products/{id}", name="admin_product_detail")
     */

    public function DetailProduct(Products $product)
    {
      // $now = new DateTime();
      // $interval = $now->diff($product->getWarrantyDate());
      dump($product);
      return $this->render('admin/product.html.twig', [
          'product' => $product
          // 'interval' => $interval
      ]);
    }


}