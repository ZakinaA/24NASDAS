<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccueilController extends AbstractController
{
    #[Route('/resp/accueil', name: 'app_accueil_uti')]
    public function index(): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si un utilisateur es    t connecté et récupérer le responsable
        $responsable = $user ? $user->getResponsable() : null;

        return $this->render('accueil/accueil_uti.html.twig', [
            'responsable' => $responsable,
        ]);
    }

    #[Route('/admin/accueil', name: 'app_accueil_admin')]
    public function index_admin(): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si un utilisateur es    t connecté et récupérer le responsable
        $responsable = $user ? $user->getResponsable() : null;

        return $this->render('accueil/accueil_admin.html.twig', [
            'responsable' => $responsable,
        ]);
    }
}