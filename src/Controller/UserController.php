<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

use App\Form\UserType;
use App\Form\UserModifierType;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/admin/user/lister', name: 'app_user_lister')]
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

    #[Route('/admin/user/modifier/{id}', name: 'app_user_modifier')]
    public function modifierUser(ManagerRegistry $doctrine, $id, Request $request){

        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();
 
        $users = $doctrine->getRepository(User::class)->find($id);
     
        if (!$user) {
            throw $this->createNotFoundException('Aucun utilisateur trouvé avec le numéro '.$id);
        }
        else
        {
                $form = $this->createForm(UserModifierType::class, $users);
                $form->handleRequest($request);
     
                if ($form->isSubmitted() && $form->isValid()) {
     
                     $users = $form->getData();
                     $entityManager = $doctrine->getManager();
                     $entityManager->persist($users);
                     $entityManager->flush();
                     return $this->render('user/consulter.html.twig', ['users' => $users, 'responsable' => $responsable]);
               }
               else{
                    return $this->render('user/ajouter.html.twig', array('form' => $form->createView(),'responsable' => $responsable));
               }
            }
     }

    #[Route('/admin/user/supprimer/{id}', name: 'app_user_supprimer')]
    public function supprimeruser(ManagerRegistry $doctrine, int $id): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Aucun Utilisateur trouvé avec l\'ID '.$id);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($user); 
        $entityManager->flush();

        return $this->redirectToRoute('app_user_lister');
    }
}
