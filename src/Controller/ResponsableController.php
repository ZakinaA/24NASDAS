<?php

namespace App\Controller;

use App\Form\ResponsableModifierType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Responsable;

use App\Form\ResponsableUserType;
use App\Form\ResponsableUserModifierType;

class ResponsableController extends AbstractController
{
    #[Route(path: '/responsable/modifier', name: 'responsable_modifier')]
    public function modifierResponsable(ManagerRegistry $doctrine, Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();
        if (!$responsable) {
            throw $this->createAccessDeniedException("Aucun responsable associé à cet utilisateur.");
        }

        // Créer le formulaire avec les informations du responsable
        $form = $this->createForm(ResponsableModifierType::class, $responsable);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($responsable);
            $entityManager->flush();

            // Ajouter un message flash et rediriger
            $this->addFlash('success', 'Vos informations ont été mises à jour avec succès.');

            return $this->redirectToRoute('responsable_modifier');
        }

        // Afficher la page avec le formulaire
        return $this->render('responsable/modifier.html.twig', [
            'form' => $form->createView(),
            'responsable' => $responsable
        ]);
    }

    // Application d'un responsable concernant un utilisateur

    #[Route('/responsable/modifier/{id}', name: 'app_responsable_user_modifier')]
    public function modifierResponsableUser(ManagerRegistry $doctrine, $id, Request $request){

        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();

       $responsables = $doctrine->getRepository(Responsable::class)->find($id);
    
       if (!$responsables) {
           throw $this->createNotFoundException('Aucun responsable trouvé avec le numéro '.$id);
       }
       else
       {
               $users = $responsables->getCompte();
               $form = $this->createForm(ResponsableUserModifierType::class, $responsables);
               $form->handleRequest($request);
    
               if ($form->isSubmitted() && $form->isValid()) {
                    $responsables = $form->getData();
                    $entityManager = $doctrine->getManager();
                    $entityManager->persist($responsables);
                    $entityManager->flush();

                    // parametres a supr
                    return $this->render('user/consulter.html.twig', [
                        'users' => $users,
                        'responsable' => $responsable
                    ]);
              }
              else{
                    return $this->render('responsable/ajouter.html.twig', array('form' => $form->createView(),));
              }
           }
    }

    #[Route('/responsable/supprimer/{id}', name: 'app_responsable_user_supprimer')]
    public function supprimerResponsable(ManagerRegistry $doctrine, int $id): Response
    {
        $responsables = $doctrine->getRepository(User::class)->find($id);

        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();

        if (!$responsables) {
            throw $this->createNotFoundException('Aucun Responsable trouvé avec l\'ID '.$id);
        }

        $users = $responsables->getCompte();

        $entityManager = $doctrine->getManager();
        $entityManager->remove($responsables); 
        $entityManager->flush();

        return $this->render('user/consulter.html.twig', ['responsables' => $responsables,'responsable' => $responsable,'users' => $users,]);
    }
}
