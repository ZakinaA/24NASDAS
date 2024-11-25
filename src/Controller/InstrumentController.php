<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Instrument;
use Symfony\Component\HttpFoundation\Request;
use App\Form\InstrumentType;
use App\Repository\TypeInstrumentRepository;

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

    public function consulterInstrument(ManagerRegistry $doctrine, int $id)
    {
        // Récupérer l'instrument par son ID
        $instrument = $doctrine->getRepository(Instrument::class)->find($id);

        if (!$instrument) {
            throw $this->createNotFoundException(
                'Aucun instrument trouvé avec le numéro ' . $id
            );
        }

        // Vérification et formatage de la date d'achat
        $formattedDate = null;
        if ($instrument->getDateAchat() !== null) {
            $formattedDate = $instrument->getDateAchat()->format('Y-m-d'); // Formater la date au format 'YYYY-MM-DD'
        } else {
            $formattedDate = "Date non définie"; // Si la date n'est pas définie
        }

        // Passer l'instrument et la date formatée à la vue
        return $this->render('instrument/consulter.html.twig', [
            'instrument' => $instrument,
            'formattedDate' => $formattedDate,
        ]);
    }

    #[Route('/instrument/ajouter', name: 'app_instrument_ajouter')]
    public function ajouterInstrument(ManagerRegistry $doctrine,Request $request){
        $instrument = new instrument();
        $form = $this->createForm(InstrumentType::class, $instrument);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
                $instrument = $form->getData();
    
                $entityManager = $doctrine->getManager();
                $entityManager->persist($cours);
                $entityManager->flush();
    
            return $this->render('instrument/consulter.html.twig', ['instrument' => $instrument,]);
        }
        else
            {
                return $this->render('instrument/ajouter.html.twig', array('form' => $form->createView(),));
        }
    }

}
