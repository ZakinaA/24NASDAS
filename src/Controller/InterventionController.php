<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Intervention;
use Symfony\Component\HttpFoundation\Request;
use App\Form\InterventionType;
use App\Form\InterventionModifierType;




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


    #[Route('/intervention/ajouter', name: 'app_intervention_ajouter')]

    public function ajouterIntervention(ManagerRegistry $doctrine,Request $request){
        $intervention = new intervention();
        $form = $this->createForm(InterventionType::class, $intervention);
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

    #[Route('/intervention/modifier/{id}', name: 'app_intervention_modifier')]
    public function modifierIntervention(ManagerRegistry $doctrine, $id, Request $request){
 
        $intervention = $doctrine->getRepository(Intervention::class)->find($id);
     
        if (!$intervention) {
            throw $this->createNotFoundException('Aucun intervention trouvé avec le numéro '.$id);
        }
        else
        {
                $form = $this->createForm(InterventionModifierType::class, $intervention);
                $form->handleRequest($request);
     
                if ($form->isSubmitted() && $form->isValid()) {
     
                     $intervention = $form->getData();
                     $entityManager = $doctrine->getManager();
                     $entityManager->persist($intervention);
                     $entityManager->flush();
                     return $this->render('intervention/consulter.html.twig', ['intervention' => $intervention,]);
               }
               else{
                    return $this->render('intervention/ajouter.html.twig', array('form' => $form->createView(),));
               }
            }
     }
     #[Route('/intervention/supprimer/{id}', name: 'app_intervention_supprimer')]
     public function supprimerIntervention(ManagerRegistry $doctrine, int $id): Response
    {
        $intervention = $doctrine->getRepository(Intervention::class)->find($id);

        if (!$intervention) {
            throw $this->createNotFoundException('Aucune intervention trouvé avec l\'ID '.$id);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($intervention); 
        $entityManager->flush();

        return $this->redirectToRoute('app_intervention_lister');
    }

 
    
    
    
    
    
    
    
    
    
    

}
