<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Eleve;
use Symfony\Component\HttpFoundation\Request;

class EleveController extends AbstractController
{
    //#[Route('/eleve', name: 'app_eleve')]
    public function index(): Response
    {
        return $this->render('eleve/index.html.twig', [
            'controller_name' => 'EleveController',
        ]);
    }

    public function listerEleves(ManagerRegistry $doctrine){

        $repository = $doctrine->getRepository(Eleve::class);

        $eleves= $repository->findAll();
        return $this->render('eleve/lister.html.twig', [
            'pEleves' => $eleves,]);	
            
    }
}
