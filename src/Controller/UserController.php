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

    #[Route('/user/consulter/{id}', name: 'app_user_consulter')]
    public function consulterUser(ManagerRegistry $doctrine, int $id)
    {
        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();
        
        // Récupérer l'instrument par son ID
        $users = $doctrine->getRepository(User::class)->find($id);

        if (!$users) {
            throw $this->createNotFoundException(
                'Aucun utilisateur trouvé avec le numéro ' . $id
            );
        }

        // Passer l'instrument et la date formatée à la vue
        return $this->render('user/consulter.html.twig', [
            'users' => $users,
            'responsable' => $responsable
        ]);
    }
}
