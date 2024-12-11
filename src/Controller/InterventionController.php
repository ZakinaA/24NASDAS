<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Intervention;
use App\Entity\Instrument;
use Symfony\Component\HttpFoundation\Request;
use App\Form\InterventionType;
use App\Form\InterventionModifierType;
use App\Form\InterventionInstrumentType;
use App\Form\InterventionInstrumentModifierType;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;




class InterventionController extends AbstractController
{
    #[Route('/intervention', name: 'app_intervention')]
    public function index(): Response
    {
        return $this->render('intervention/index.html.twig', [
            'controller_name' => 'InterventionController',
        ]);
    }

    #[Route('/intervention/lister', name: 'app_intervention_lister')]
    public function listerIntervention(ManagerRegistry $doctrine){

        $repository = $doctrine->getRepository(Intervention::class);

        $intervention= $repository->findAll();
        return $this->render('intervention/lister.html.twig', [
            'pIntervention' => $intervention,]);	
            
    }

    
    #[Route('/intervention/consulter/{id}', name: 'app_intervention_consulter')]

    public function consulterIntervention(ManagerRegistry $doctrine, int $id){

		$intervention= $doctrine->getRepository(Intervention::class)->find($id);

		if (!$intervention) {
			throw $this->createNotFoundException(
            'Aucune intervention trouvé avec le numéro '.$id
			);
		}

		return $this->render('intervention/consulter.html.twig', [
            'intervention' => $intervention,]);
	}

    #[Route('/intervention/ajouter/{id}', name: 'app_intervention_instrument_ajouter')]
    public function ajouterInterventionByInstrument(ManagerRegistry $doctrine,Request $request, $id){
        $instrument = $doctrine->getRepository(Instrument::class)->find($id);

        if (!$instrument) {
            throw $this->createNotFoundException('Instrument non trouvé.');
        }

        $intervention = new intervention();
        $intervention->setInstrument($instrument);

        $form = $this->createForm(InterventionInstrumentType::class, $intervention);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
                $intervention = $form->getData();
    
                $entityManager = $doctrine->getManager();
                $entityManager->persist($intervention);
                $entityManager->flush();
    
            return $this->render('intervention/consulter.html.twig', ['intervention' => $intervention,]);
        }
        else
            {
                return $this->render('intervention/ajouter.html.twig', array('form' => $form->createView(),));
        }
    }

    #[Route('/intervention/modifier/{id}', name: 'app_intervention_instrument_modifier')]

    public function modifierIntervention(ManagerRegistry $doctrine, $id, Request $request){

       $intervention = $doctrine->getRepository(Intervention::class)->find($id);
       $instrument = $intervention->getInstrument();

    
       if (!$intervention) {
           throw $this->createNotFoundException('Aucune intervention trouvé avec le numéro '.$id);
       }
       else
       {
               $form = $this->createForm(InterventionInstrumentModifierType::class, $intervention);
               $form->handleRequest($request);
    
               if ($form->isSubmitted() && $form->isValid()) {
                    $intervention = $form->getData();
                    $entityManager = $doctrine->getManager();
                    $entityManager->persist($intervention);
                    $entityManager->flush();

                    // Vérification et formatage de la date d'achat
                    $formattedDateAchat = null;
                    if ($instrument->getDateAchat() !== null) {
                    $formattedDateAchat = $instrument->getDateAchat()->format('d/m/Y'); // Formater la date au format 'YYYY-MM-DD'
                    } else {
                    $formattedDateAchat = "Date non définie"; // Si la date n'est pas définie
                    }
                    // parametres a supr
                    return $this->render('instrument/consulter.html.twig', [
                        'instrument' => $instrument,
                        'formattedDateAchat' => $formattedDateAchat,
                        'intervention' => $intervention,
                    ]);
              }
              else{
                    return $this->render('intervention/ajouter.html.twig', array('form' => $form->createView(),));
              }
           }
    }




    

    #[Route('/intervention/supprimer/{id}', name: 'app_intervention_instrument_supprimer')]
    public function supprimerIntervention(ManagerRegistry $doctrine, int $id): Response
    {
        $intervention = $doctrine->getRepository(Intervention::class)->find($id);

        if (!$intervention) {
            throw $this->createNotFoundException('Aucune intervention trouvé avec l\'ID '.$id);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($intervention); 
        $entityManager->flush();

        return $this->render('intervention/consulter.html.twig', ['intervention' => $intervention,]);
    }

    

}
