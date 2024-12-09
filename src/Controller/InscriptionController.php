<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Cours;
use App\Entity\Eleve;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Inscription;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription_index')]
    public function index(): Response
    {
        return $this->render('inscription/index.html.twig', [
            'controller_name' => 'InscriptionController',
        ]);
    }


    #[Route('/inscrire/{eleveId}', name: 'app_inscription', methods: ['POST', 'GET'])]
    public function inscrireEleve(
        int $eleveId,
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();
        $eleve = $entityManager->getRepository(Eleve::class)->find($eleveId);

        if (!$eleve) {
            throw $this->createNotFoundException('Élève introuvable');
        }

        // Récupérer tous les cours auxquels l'élève est déjà inscrit
        $inscriptionsExistantes = $entityManager->getRepository(Inscription::class)
            ->findBy(['eleve' => $eleve]);

        // Récupérer les IDs des cours auxquels l'élève est déjà inscrit
        $coursInscrits = [];
        foreach ($inscriptionsExistantes as $inscription) {
            $coursInscrits[] = $inscription->getCours()->getId();
        }

        // Récupérer tous les cours disponibles pour l'inscription (en excluant ceux déjà inscrits)
        $coursDisponibles = $entityManager->getRepository(Cours::class)
            ->findBy(['id' => array_diff(array_map(fn($cours) => $cours->getId(), $entityManager->getRepository(Cours::class)->findAll()), $coursInscrits)]);

        // Si le formulaire est soumis
        if ($request->isMethod('POST')) {
            $coursIds = $request->request->all('coursIds');

            foreach ($coursIds as $coursId) {
                $cours = $entityManager->getRepository(Cours::class)->find($coursId);

                if ($cours) {
                    // Vérifier si l'élève est déjà inscrit à ce cours
                    $existingInscription = $entityManager->getRepository(Inscription::class)
                        ->findOneBy(['eleve' => $eleve, 'cours' => $cours]);

                    if (!$existingInscription) {
                        // Si l'inscription n'existe pas déjà, créer une nouvelle inscription
                        $inscription = new Inscription();
                        $inscription->setEleve($eleve)
                                    ->setCours($cours)
                                    ->setDateInscription(new \DateTime());

                        $entityManager->persist($inscription);
                    }
                }
            }

            $entityManager->flush();

            $this->addFlash('success', 'L\'élève a été inscrit aux cours sélectionnés.');
            return $this->redirectToRoute('app_eleve_consulter', ['id' => $eleveId]);
        }

        return $this->render('inscription/inscription.html.twig', [
            'eleve' => $eleve,
            'coursDisponibles' => $coursDisponibles,
        ]);
    }

    #[Route('/inscription/supprimer/{id}', name: 'app_inscription_supprimer')]
    public function supprimerInscription(ManagerRegistry $doctrine, int $id): Response
    {
        $inscription = $doctrine->getRepository(Inscription::class)->find($id);

        if (!$inscription) {
            throw $this->createNotFoundException('Aucun inscription trouvé avec l\'ID '.$id);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($inscription); 
        $entityManager->flush();

        return $this->redirectToRoute('app_eleve_lister');
    }

}
