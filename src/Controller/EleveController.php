<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Eleve;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EleveType;
use App\Entity\Cours;
use App\Entity\Inscription;
use App\Form\EleveModifierType;
use App\Repository\EleveRepository;
use Doctrine\ORM\Mapping as ORM;

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

    
    #[Route('/mes-eleves', name: 'mes_eleves')]
    public function listerMesEleves(EleveRepository $eleveRepository): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();
        if (!$responsable) {
            throw $this->createAccessDeniedException("Aucun responsable associé à cet utilisateur.");
        }

        // Récupérer les élèves du responsable
        $eleves = $eleveRepository->findBy(['responsable' => $responsable]);

        return $this->render('eleve/mes_eleves.html.twig', [
            'eleves' => $eleves,
            'responsable' => $responsable, 
        ]);
    }

    public function consulterEleve(ManagerRegistry $doctrine, Request $request, int $id): Response
    {
        // Récupérer l'élève par son ID
        $eleve = $doctrine->getRepository(Eleve::class)->find($id);

        if (!$eleve) {
            throw $this->createNotFoundException(
                'Aucun élève trouvé avec le numéro ' . $id
            );
        }

        // Récupérer tous les cours disponibles
        $coursDisponibles = $doctrine->getRepository(Cours::class)->findAll();

        // Récupérer les inscriptions de l'élève et les cours auxquels il est inscrit
        $inscriptions = $doctrine->getRepository(Inscription::class)->findBy(['eleve' => $eleve]);
        $coursInscrits = [];
        foreach ($inscriptions as $inscription) {
            $coursInscrits[] = $inscription->getCours();
        }

        // Gérer l'inscription (si le formulaire est soumis)
        if ($request->isMethod('POST')) {
            $coursIds = $request->request->get('coursIds');
            if ($coursIds) {
                foreach ($coursIds as $coursId) {
                    $cours = $doctrine->getRepository(Cours::class)->find($coursId);
                    if ($cours) {
                        // Créer une nouvelle inscription
                        $inscription = new Inscription();
                        $inscription->setEleve($eleve)
                                    ->setCours($cours)
                                    ->setDateInscription(new \DateTime()); // Date actuelle
                        
                        // Enregistrer l'inscription
                        $em = $doctrine->getManager();
                        $em->persist($inscription);
                        $em->flush();
                    }
                }
                $this->addFlash('success', 'L\'élève a été inscrit à(ux) cours sélectionné(s).');
            }
        }

        dump($coursInscrits);
        // Retourner la page de consultation de l'élève avec les cours disponibles et inscrits
        return $this->render('eleve/consulter.html.twig', [
            'eleve' => $eleve,
            'coursDisponibles' => $coursDisponibles, // Passer la variable des cours disponibles
            'coursInscrits' => $coursInscrits, // Passer les cours auxquels l'élève est inscrit
            'inscriptions' => $inscriptions
        ]);
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
