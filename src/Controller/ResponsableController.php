<?php

namespace App\Controller;

use App\Form\ResponsableModifierType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

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
}
