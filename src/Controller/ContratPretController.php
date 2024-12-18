<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\ContratPret;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContratPretType;
use App\Form\ContratPretModifierType;




class ContratPretController extends AbstractController
{
    //#[Route('/contrat/pret', name: 'app_contrat_pret')]
    public function index(): Response
    {
        return $this->render('contrat_pret/index.html.twig', [
            'controller_name' => 'ContratPretController',
        ]);
    }

    #[Route('/admin/contratPret/lister', name: 'app_contratPret_lister')]
    public function listerContratPret(ManagerRegistry $doctrine){

        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();


        $repository = $doctrine->getRepository(ContratPret::class);

        $contratPret= $repository->findAll();
        return $this->render('contratPret/lister.html.twig', [
            'pContratsPret' => $contratPret,
            'responsable' => $responsable, 
        ]);	
            
    }

    
    #[Route('/admin/contratPret/consulter/{id}', name: 'app_contratPret_consulter')]

    public function consulterContratPret(ManagerRegistry $doctrine, int $id){
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();
  
		$contratPret= $doctrine->getRepository(ContratPret::class)->find($id);

		if (!$contratPret) {
			throw $this->createNotFoundException(
            'Aucun contrat trouvé avec le numéro '.$id
			);
		}

		return $this->render('contratPret/consulter.html.twig', [
            'contratPret' => $contratPret,
            'responsable' => $responsable, 
        ]);
	}


    #[Route('/admin/contratPret/ajouter', name: 'app_contratPret_ajouter')]

    public function ajouterContratPret(ManagerRegistry $doctrine,Request $request){

        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();
  
        $contratPret = new contratPret();
        $form = $this->createForm(ContratPretType::class, $contratPret);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
                $contratPret = $form->getData();
    
                $entityManager = $doctrine->getManager();
                $entityManager->persist($contratPret);
                $entityManager->flush();
    
            return $this->render('contratPret/consulter.html.twig', ['contratPret' => $contratPret, 'responsable' => $responsable, ]);
        }
        else
            {
                return $this->render('contratPret/ajouter.html.twig', array('form' => $form->createView(), 'responsable' => $responsable, ));
        }
    }

    #[Route('/admin/contratPret/modifier/{id}', name: 'app_contratPret_modifier')]
    public function modifierContratPret(ManagerRegistry $doctrine, $id, Request $request){

        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();
 
        $contratPret = $doctrine->getRepository(ContratPret::class)->find($id);
     
        if (!$contratPret) {
            throw $this->createNotFoundException('Aucun contratPret trouvé avec le numéro '.$id);
        }
        else
        {
                $form = $this->createForm(ContratPretModifierType::class, $contratPret);
                $form->handleRequest($request);
     
                if ($form->isSubmitted() && $form->isValid()) {
     
                     $contratPret = $form->getData();
                     $entityManager = $doctrine->getManager();
                     $entityManager->persist($contratPret);
                     $entityManager->flush();
                     return $this->render('contratPret/consulter.html.twig', ['contratPret' => $contratPret, 'responsable' => $responsable,]);
               }
               else{
                    return $this->render('contratPret/ajouter.html.twig', array('form' => $form->createView(), 'responsable' => $responsable,));
               }
            }
     }
     #[Route('/admin/contratPret/supprimer/{id}', name: 'app_contratPret_supprimer')]
     public function supprimerContratPret(ManagerRegistry $doctrine, int $id): Response
    {
        $contratPret = $doctrine->getRepository(ContratPret::class)->find($id);

        if (!$contratPret) {
            throw $this->createNotFoundException('Aucun contrat trouvé avec l\'ID '.$id);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($contratPret); 
        $entityManager->flush();

        return $this->redirectToRoute('app_contratPret_lister');
    }

 
    
    
    
    
    
    
    
    
    
    

}
