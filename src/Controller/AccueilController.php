<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil_uti')]
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
}
