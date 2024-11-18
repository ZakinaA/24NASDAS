<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Eleve;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EleveType;
use App\Form\EleveModifierType;

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

    public function consulterEleve(ManagerRegistry $doctrine, int $id){

		$eleve= $doctrine->getRepository(Eleve::class)->find($id);

		if (!$eleve) {
			throw $this->createNotFoundException(
            'Aucun élève trouvé avec le numéro '.$id
			);
		}

		return $this->render('eleve/consulter.html.twig', [
            'eleve' => $eleve,]);
	}

    public function ajouterEleve(ManagerRegistry $doctrine,Request $request){
        $eleve = new eleve();
        $form = $this->createForm(EleveType::class, $eleve);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
                $eleve = $form->getData();
    
                $entityManager = $doctrine->getManager();
                $entityManager->persist($eleve);
                $entityManager->flush();
    
            return $this->render('eleve/consulter.html.twig', ['eleve' => $eleve,]);
        }
        else
            {
                return $this->render('eleve/ajouter.html.twig', array('form' => $form->createView(),));
        }
    }

    public function supprimerEleve(ManagerRegistry $doctrine, int $id): Response
    {
        $eleve = $doctrine->getRepository(Eleve::class)->find($id);

        if (!$eleve) {
            throw $this->createNotFoundException('Aucun élève trouvé avec l\'ID '.$id);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($eleve); 
        $entityManager->flush();

        return $this->redirectToRoute('app_eleve_lister');
    }

    public function modifierEleve(ManagerRegistry $doctrine, $id, Request $request){
 
        $eleve = $doctrine->getRepository(Eleve::class)->find($id);
     
        if (!$eleve) {
            throw $this->createNotFoundException('Aucun élève trouvé avec le numéro '.$id);
        }
        else
        {
                $form = $this->createForm(EleveModifierType::class, $eleve);
                $form->handleRequest($request);
     
                if ($form->isSubmitted() && $form->isValid()) {
     
                     $eleve = $form->getData();
                     $entityManager = $doctrine->getManager();
                     $entityManager->persist($eleve);
                     $entityManager->flush();
                     return $this->render('eleve/consulter.html.twig', ['eleve' => $eleve,]);
               }
               else{
                    return $this->render('eleve/ajouter.html.twig', array('form' => $form->createView(),));
               }
            }
     }
}