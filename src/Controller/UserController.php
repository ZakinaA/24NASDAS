<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/lister', name: 'app_user_lister')]
    public function listerUsers(ManagerRegistry $doctrine){

        $repository = $doctrine->getRepository(User::class);
        
        $user = $this->getUser();

        // Vérifier si un utilisateur es    t connecté et récupérer le responsable
        $responsable = $user ? $user->getResponsable() : null;

        $users= $repository->findAll();
        return $this->render('user/lister.html.twig', [
            'pUsers' => $users,
            'responsable' => $responsable,]);	
            
    }
}
