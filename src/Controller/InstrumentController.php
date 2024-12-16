<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Instrument;
use App\Entity\Intervention;
use Symfony\Component\HttpFoundation\Request;
use App\Form\InstrumentType;
use App\Form\InstrumentModifierType;
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

        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();


        $repository = $doctrine->getRepository(Instrument::class);

        $instrument= $repository->findAll();
        return $this->render('instrument/lister.html.twig', [
            'pInstruments' => $instrument,
            'responsable' => $responsable, 
        ]);	
            
    }

    public function listerAdminInstrument(ManagerRegistry $doctrine){

        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();


        $repository = $doctrine->getRepository(Instrument::class);

        $instrument= $repository->findAll();
        return $this->render('instrument/lister_admin.html.twig', [
            'pInstruments' => $instrument,
            'responsable' => $responsable, 
        ]);	
            
    }
    public function consulterInstrument(ManagerRegistry $doctrine, int $id)
    {
        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();
        
        // Récupérer l'instrument par son ID
        $instrument = $doctrine->getRepository(Instrument::class)->find($id);

        if (!$instrument) {
            throw $this->createNotFoundException(
                'Aucun instrument trouvé avec le numéro ' . $id
            );
        }

        $interventions = $instrument->getIntervention();

        // Vérification et formatage de la date d'achat
        $formattedDateAchat = null;
        if ($instrument->getDateAchat() !== null) {
            $formattedDateAchat = $instrument->getDateAchat()->format('d/m/Y'); // Formater la date au format 'YYYY-MM-DD'
        } else {
            $formattedDateAchat = "Date non définie"; // Si la date n'est pas définie
        }

        // Passer l'instrument et la date formatée à la vue
        return $this->render('instrument/consulter.html.twig', [
            'instrument' => $instrument,
            'formattedDateAchat' => $formattedDateAchat,
            'interventions' => $interventions,
            'responsable' => $responsable
        ]);
    }

    #[Route('/instrument/ajouter', name: 'app_instrument_ajouter')]
    public function ajouterInstrument(ManagerRegistry $doctrine,Request $request){
        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();

        $instrument = new instrument();
        $form = $this->createForm(InstrumentType::class, $instrument);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
                $instrument = $form->getData();

                $formattedDate = $instrument->getDateAchat()->format('Y-m-d');
                
                $entityManager = $doctrine->getManager();
                $entityManager->persist($instrument);
                $entityManager->flush();
    
            return $this->render('instrument/consulter.html.twig', ['instrument' => $instrument,'formattedDate' => $formattedDate,'responsable' => $responsable]);
        }
        else
            {
                return $this->render('instrument/ajouter.html.twig', array('form' => $form->createView(),));
        }
    }

    #[Route('/instrument/modifier/{id}', name: 'app_instrument_modifier')]
    public function modifierCours(ManagerRegistry $doctrine, $id, Request $request){
 
        $instrument = $doctrine->getRepository(Instrument::class)->find($id);
        $formattedDate = $instrument->getDateAchat()->format('Y-m-d');
     
        if (!$instrument) {
            throw $this->createNotFoundException('Aucun cours trouvé avec le numéro '.$id);
        }
        else
        {
                $form = $this->createForm(InstrumentModifierType::class, $instrument);
                $form->handleRequest($request);
     
                if ($form->isSubmitted() && $form->isValid()) {
     
                     $instrument = $form->getData();
                     $entityManager = $doctrine->getManager();
                     $entityManager->persist($instrument);
                     $entityManager->flush();
                     return $this->render('instrument/consulter.html.twig', ['instrument' => $instrument,'formattedDate' => $formattedDate,]);
               }
               else{
                    return $this->render('instrument/ajouter.html.twig', array('form' => $form->createView(),));
               }
            }
     }

    #[Route('/instrument/supprimer/{id}', name: 'app_instrument_supprimer')]
    public function supprimerCours(ManagerRegistry $doctrine, int $id): Response
    {
        $instrument = $doctrine->getRepository(Instrument::class)->find($id);

        if (!$instrument) {
            throw $this->createNotFoundException('Aucun instrument trouvé avec l\'ID '.$id);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($instrument); 
        $entityManager->flush();

        return $this->redirectToRoute('app_instrument_lister');
    }

}
