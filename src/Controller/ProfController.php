<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Professeur;
use App\Form\ProfesseurType;
use App\Form\ProfesseurModifierType;

class ProfController extends AbstractController
{
    #[Route('/professeur', name: 'app_professeur')]
    public function index(): Response
    {
        return $this->render('prof/index.html.twig', [
            'controller_name' => 'ProfController',
        ]);
    }

    #[Route('/professeur/lister', name: 'app_pofesseur_lister')]
    public function listerProfesseur(ManagerRegistry $doctrine){

        $repository = $doctrine->getRepository(Professeur::class);

        $professeur= $repository->findAll();
        return $this->render('professeur/lister.html.twig', [
            'pProfesseurs' => $professeur,]);	
            
    }

    #[Route('/professeur/consulter/{id}', name: 'app_professeur_consulter')]
    public function consulterProfesseur(ManagerRegistry $doctrine, int $id)
    {
        // Récupérer l'instrument par son ID
        $professeur = $doctrine->getRepository(Professeur::class)->find($id);

        if (!$professeur) {
            throw $this->createNotFoundException(
                'Aucun professeur trouvé avec le numéro ' . $id
            );
        }

        $cours = $professeur->getCours();

        // Passer l'instrument et la date formatée à la vue
        return $this->render('professeur/consulter.html.twig', [
            'professeur' => $professeur,
            'cours' => $cours,
        ]);
    }

    #[Route('/professeur/ajouter', name: 'app_professeur_ajouter')]
    public function ajouterProfesseur(ManagerRegistry $doctrine,Request $request){
        $professeur = new Professeur();
        $form = $this->createForm(ProfesseurType::class, $professeur);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
                $professeur = $form->getData();
                
                $entityManager = $doctrine->getManager();
                $entityManager->persist($professeur);
                $entityManager->flush();
    
            return $this->render('professeur/consulter.html.twig', ['professeur' => $professeur,]);
        }
        else
            {
                return $this->render('professeur/ajouter.html.twig', array('form' => $form->createView(),));
        }
    }

    #[Route('/professeur/modifier/{id}', name: 'app_professeur_modifier')]
    public function modifierProfesseur(ManagerRegistry $doctrine, $id, Request $request){
 
        $professeur = $doctrine->getRepository(Professeur::class)->find($id);
     
        if (!$professeur) {
            throw $this->createNotFoundException('Aucun professeur trouvé avec le numéro '.$id);
        }
        else
        {
                $form = $this->createForm(ProfesseurModifierType::class, $professeur);
                $form->handleRequest($request);
     
                if ($form->isSubmitted() && $form->isValid()) {
     
                     $professeur = $form->getData();
                     $entityManager = $doctrine->getManager();
                     $entityManager->persist($professeur);
                     $entityManager->flush();
                     return $this->render('professeur/consulter.html.twig', ['professeur' => $professeur,]);
               }
               else{
                    return $this->render('professeur/ajouter.html.twig', array('form' => $form->createView(),));
               }
            }
     }
}
