<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Instrument;
use App\Entity\Accessoire;

use App\Form\AccessoireType;
use App\Form\AccessoireModifierType;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class AccessoireController extends AbstractController
{
    #[Route('/accessoire', name: 'app_accessoire')]
    public function index(): Response
    {
        return $this->render('accessoire/index.html.twig', [
            'controller_name' => 'AccessoireController',
        ]);
    }

    #[Route('/admin/accessoire/ajouter/{id}', name: 'app_accessoire_instrument_ajouter')]
    public function ajouterAccessoireByInstrument(ManagerRegistry $doctrine,Request $request, $id){
        // Récupérer l'instrument à partir de l'ID
        $instrument = $doctrine->getRepository(Instrument::class)->find($id);

        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();

        if (!$instrument) {
            throw $this->createNotFoundException('Instrument non trouvé.');
        }

        // Créer un nouvel accessoire
        $accessoire = new Accessoire();

        // Lier l'instrument à l'accessoire
        $accessoire->addInstrument($instrument); // Correct

        // Créer et traiter le formulaire
        $form = $this->createForm(AccessoireType::class, $accessoire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire et persister l'accessoire
            $accessoire = $form->getData();

            $entityManager = $doctrine->getManager();
            $entityManager->persist($accessoire);
            $entityManager->flush();

            // Rediriger ou afficher la vue avec l'accessoire
            return $this->render('instrument/consulter.html.twig', [
                'instrument' => $instrument,
                'responsable' => $responsable,
            ]);
        } else {
            // Rendu du formulaire si il n'est pas soumis ou valide
            return $this->render('accessoire/ajouter.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

    #[Route('/admin/accessoire/modifier/{id}', name: 'app_accessoire_instrument_modifier')]
    public function modifierAccessoire(ManagerRegistry $doctrine, $id, Request $request){

       // Trouver l'accessoire par son ID
        $accessoire = $doctrine->getRepository(Accessoire::class)->find($id);

        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();
        
        // Vérifier que l'accessoire existe
        if (!$accessoire) {
            throw $this->createNotFoundException('Aucun accessoire trouvé avec le numéro '.$id);
        }
        
        // Récupérer la collection d'instruments associés à cet accessoire
        $instruments = $accessoire->getInstrument();
        
        // Si la collection d'instruments est vide, gérer le cas
        if ($instruments->isEmpty()) {
            throw $this->createNotFoundException('Aucun instrument trouvé pour cet accessoire.');
        }

        // Si vous voulez accéder au premier instrument de la collection
        $instrument = $instruments->first(); // Exemple pour récupérer le premier instrument
        
        // Créer et traiter le formulaire
        $form = $this->createForm(AccessoireModifierType::class, $accessoire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les données de l'accessoire
            $accessoire = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($accessoire);
            $entityManager->flush();

            // Retourner la vue en passant le premier instrument de la collection
            return $this->render('instrument/consulter.html.twig', [
                'instrument' => $instrument,
                'responsable' => $responsable,
            ]);
        } else {
            // Afficher le formulaire s'il n'est pas soumis ou valide
            return $this->render('accessoire/ajouter.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

    #[Route('/admin/accessoire/supprimer/{id}', name: 'app_accessoire_instrument_supprimer')]
    public function supprimerAccessoire(ManagerRegistry $doctrine, int $id): Response
    {
        // Trouver l'accessoire avec l'ID
        $accessoire = $doctrine->getRepository(Accessoire::class)->find($id);

        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();

        if (!$accessoire) {
            throw $this->createNotFoundException('Aucun accessoire trouvé avec l\'ID '.$id);
        }

        // Obtenir la collection d'instruments associés à cet accessoire
        $instruments = $accessoire->getInstrument();  // Cette méthode devrait exister maintenant

        // Si la collection d'instruments est vide, vous pouvez gérer cette situation
        if ($instruments->isEmpty()) {
            throw $this->createNotFoundException('Aucun instrument associé à cet accessoire.');
        }

        // Exemple : accéder au premier instrument et récupérer la date d'achat
        $instrument = $instruments->first();  // Vous pouvez aussi utiliser un autre critère de sélection
        $dateAchat = $instrument->getDateAchat();  // Assurez-vous que `dateAchat` existe dans l'entité Instrument

        // Suppression de l'accessoire
        $entityManager = $doctrine->getManager();
        $entityManager->remove($accessoire); 
        $entityManager->flush();

        // Retourner une réponse, avec l'instrument et sa date d'achat si nécessaire
        return $this->render('instrument/consulter.html.twig', ['instrument' => $instrument, 'responsable' => $responsable,]);
    }
}