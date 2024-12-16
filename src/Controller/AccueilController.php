<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AccueilController extends AbstractController
{
    #[Route('/resp/accueil', name: 'app_accueil_uti')]
    #[IsGranted('ROLE_RESPONSABLE')]  # Ajoutez cette annotation pour restreindre l'accès
    public function index(): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        $responsable = $user ? $user->getResponsable() : null;

        return $this->render('accueil/accueil_uti.html.twig', [
            'responsable' => $responsable,
        ]);
    }

    #[Route('/admin/accueil', name: 'app_accueil_admin')]
    #[IsGranted('ROLE_ADMIN')]  # Ajoutez cette annotation pour restreindre l'accès
    public function index_admin(): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        $responsable = $user ? $user->getResponsable() : null;

        return $this->render('accueil/accueil_admin.html.twig', [
            'responsable' => $responsable,
        ]);
    }
}
