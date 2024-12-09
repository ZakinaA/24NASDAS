<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Marque;
use App\Form\MarqueType;
use App\Form\MarqueModifierType;

class MarqueController extends AbstractController
{
    #[Route('/marque', name: 'app_marque')]
    public function index(): Response
    {
        return $this->render('prof/index.html.twig', [
            'controller_name' => 'ProfController',
        ]);
    }

    #[Route('/marque/lister', name: 'app_marque_lister')]
    public function listerMarque(ManagerRegistry $doctrine){

        $user = $this->getUser();

        $repository = $doctrine->getRepository(Marque::class);
        $responsable = $user->getResponsable();

        $marque= $repository->findAll();
        return $this->render('marque/lister.html.twig', [
            'pMarque' => $marque,
            'responsable' => $responsable]);	
            
    }
    /*
    #[Route('/marque/consulter/{id}', name: 'app_marque_consulter')]
    public function consulterMarque(ManagerRegistry $doctrine, int $id)
    {
        // Récupérer l'instrument par son ID
        $marque = $doctrine->getRepository(Marque::class)->find($id);

        if (!$marque) {
            throw $this->createNotFoundException(
                'Aucune marque trouvé avec le numéro ' . $id
            );
        }

        // Passer l'instrument à la vue
        return $this->render('marque/consulter.html.twig', [
            'marque' => $marque,
        ]);
    }*/

    #[Route('/marque/ajouter', name: 'app_marque_ajouter')]
    public function ajouterMarque(ManagerRegistry $doctrine,Request $request){
        $marque = new Marque();
        $form = $this->createForm(MarqueType::class, $marque);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
                $marque = $form->getData();
                
                $entityManager = $doctrine->getManager();
                $entityManager->persist($marque);
                $entityManager->flush();
    
                $repository = $doctrine->getRepository(Marque::class);
                $marque= $repository->findAll();
                return $this->render('marque/lister.html.twig', ['pMarque' => $marque,]);
        }
        else
            {
                return $this->render('marque/ajouter.html.twig', array('form' => $form->createView(),));
        }
    }

    #[Route('/marque/modifier/{id}', name: 'app_marque_modifier')]
    public function modifierMarque(ManagerRegistry $doctrine, $id, Request $request){
 
        $marque = $doctrine->getRepository(Marque::class)->find($id);
     
        if (!$marque) {
            throw $this->createNotFoundException('Aucune marque trouvé avec le numéro '.$id);
        }
        else
        {
                $form = $this->createForm(MarqueModifierType::class, $marque);
                $form->handleRequest($request);
     
                if ($form->isSubmitted() && $form->isValid()) {
     
                     $marque = $form->getData();
                     $entityManager = $doctrine->getManager();
                     $entityManager->persist($marque);
                     $entityManager->flush();

                     $repository = $doctrine->getRepository(Marque::class);
                     $marque= $repository->findAll();
                     return $this->render('marque/lister.html.twig', ['pMarque' => $marque,]);
               }
               else{
                    return $this->render('marque/ajouter.html.twig', array('form' => $form->createView(),));
               }
            }
     }

    #[Route('/marque/supprimer/{id}', name: 'app_marque_supprimer')]
    public function supprimerMarque(ManagerRegistry $doctrine, int $id): Response
    {
        $marque = $doctrine->getRepository(Marque::class)->find($id);

        if (!$marque) {
            throw $this->createNotFoundException('Aucune marque trouvé avec l\'ID '.$id);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($marque); 
        $entityManager->flush();

        return $this->redirectToRoute('app_marque_lister');
    }
}
