<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Instrument;
use Symfony\Component\HttpFoundation\Request;

class InstrumentController extends AbstractController
{
    //#[Route('/cours', name: 'app_cours')]
    public function index(): Response
    {
        return $this->render('instrument/index.html.twig', [
            'controller_name' => 'InstrumentController',
        ]);
    }

    public function listerInstrument(ManagerRegistry $doctrine){

        $repository = $doctrine->getRepository(Instrument::class);

        $instrument= $repository->findAll();
        return $this->render('instrument/lister.html.twig', [
            'pInstruments' => $instrument,]);	
            
    }
}
