<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Modele;
use App\Form\ModeleType;
use App\Form\ModeleModifierType;

class ModeleController extends AbstractController
{
    #[Route('/modele', name: 'app_modele')]
    public function index(): Response
    {
        return $this->render('prof/index.html.twig', [
            'controller_name' => 'ModeleController',
        ]);
    }

    #[Route('/admin/modele/lister', name: 'app_modele_lister')]
    public function listerModele(ManagerRegistry $doctrine){

        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();

        $repository = $doctrine->getRepository(Modele::class);

        $modele= $repository->findAll();
        return $this->render('modele/lister.html.twig', [
            'pModele' => $modele,
            'responsable' => $responsable, 
        ]);	
            
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

    #[Route('/admin/modele/ajouter', name: 'app_modele_ajouter')]
    public function ajouterModele(ManagerRegistry $doctrine,Request $request){

        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();

        $modele = new Modele();
        $form = $this->createForm(ModeleType::class, $modele);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
                $modele = $form->getData();
                
                $entityManager = $doctrine->getManager();
                $entityManager->persist($modele);
                $entityManager->flush();
    
                $repository = $doctrine->getRepository(Modele::class);
                $modele= $repository->findAll();
                return $this->render('modele/lister.html.twig', ['pModele' => $modele, 'responsable' => $responsable, ]);
        }
        else
            {
                return $this->render('modele/ajouter.html.twig', array('form' => $form->createView(), 'responsable' => $responsable, ));
        }
    }

    #[Route('/admin/modele/modifier/{id}', name: 'app_modele_modifier')]
    public function modifierModele(ManagerRegistry $doctrine, $id, Request $request){

        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();
 
        $modele = $doctrine->getRepository(Modele::class)->find($id);
     
        if (!$modele) {
            throw $this->createNotFoundException('Aucun modele trouvé avec le numéro '.$id);
        }
        else
        {
                $form = $this->createForm(ModeleModifierType::class, $modele);
                $form->handleRequest($request);
     
                if ($form->isSubmitted() && $form->isValid()) {
     
                     $modele = $form->getData();
                     $entityManager = $doctrine->getManager();
                     $entityManager->persist($modele);
                     $entityManager->flush();

                     $repository = $doctrine->getRepository(Modele::class);
                     $modele= $repository->findAll();
                     return $this->render('modele/lister.html.twig', ['pModele' => $modele, 'responsable' => $responsable, 
                    ]);
               }
               else{
                    return $this->render('modele/ajouter.html.twig', array('form' => $form->createView(),'responsable' => $responsable, 
                ));
               }
            }
     }

    #[Route('/admin/modele/supprimer/{id}', name: 'app_modele_supprimer')]
    public function supprimerModele(ManagerRegistry $doctrine, int $id): Response
    {
        $modele = $doctrine->getRepository(Modele::class)->find($id);

        if (!$modele) {
            throw $this->createNotFoundException('Aucun modele trouvé avec l\'ID '.$id);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($modele); 
        $entityManager->flush();

        return $this->redirectToRoute('app_modele_lister');
    }
}
