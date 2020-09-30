<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\RegistrationFormType;
use App\Form\UserFormType;

class UsersController extends AbstractController
{
    /**
    * @Route("admin/users", name="admin_users")
    */

    public function AllUsers(UsersRepository $repo)
    {
      $users = $repo->findAll();
       
      return $this->render('users/users.html.twig', [
          'users' => $users
      ]);
    }


    /**
     * @Route("admin/users/{id}/edit", name="admin_user_edit")
     */

     public function form (Users $user, Request $request, EntityManagerInterface $manager, SluggerInterface $slugger){
      
      $formUser = $this->createForm(UserFormType::class, $user);
      $formUser->HandleRequest($request);
    
      if($formUser->isSubmitted() && $formUser->isValid()){

        $manager->persist($user);
        $manager->flush();
        return $this->redirectToRoute('admin_users');
      }

      return $this->render('users/userEdit.html.twig',[
        'formUser' => $formUser->createView(),
        'editMode' => $user->getId() !== null
      ]);
    }

    /**
     * @Route("/admin/users/{id}/delete", name="admin_user_delete", methods="DELETE|GET")
    */
    
    public function DeleteUser(Users $user, Request $request, EntityManagerInterface $manager): Response
    {
      
      if (true || $this->isCsrfTokenValid('delete'. $user->getId(), $request->get('_token')))
      {
        
        $manager->remove($user);
        $manager->flush();
    
      }

      return $this->redirectToRoute('admin_users'); 

    }



}
