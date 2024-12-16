<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Cours;
use App\Form\CoursType;
use App\Form\CoursModifierType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CoursController extends AbstractController
{
    //#[Route('/cours', name: 'app_cours')]
    public function index(): Response
    {
        return $this->render('cours/index.html.twig', [
            'controller_name' => 'CoursController',
        ]);
    }

    public function listerCours(ManagerRegistry $doctrine){

        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();

        $repository = $doctrine->getRepository(Cours::class);

        $cours= $repository->findAll();
        return $this->render('cours/lister.html.twig', [
            'pCours' => $cours,
            'responsable' => $responsable, 
        ]);	
            
    }

    
    public function listerAdminCours(ManagerRegistry $doctrine){

        $user = $this->getUser();

        // Vérifier si l'utilisateur a un responsable associé
        $responsable = $user->getResponsable();

        $repository = $doctrine->getRepository(Cours::class);

        $cours= $repository->findAll();
        return $this->render('cours/lister_admin.html.twig', [
            'pCours' => $cours,
            'responsable' => $responsable, 
        ]);	
            
    }

    #[Route('/cours/lister/json', name: 'lister_cours_json', methods: ['GET'])]
    public function listerCoursJson(ManagerRegistry $doctrine)
    {
        $user = $this->getUser();

        $responsable = $user->getResponsable();

        $repository = $doctrine->getRepository(Cours::class);
        $cours = $repository->findAll();

        $coursArray = [];
        foreach ($cours as $course) {
            $coursArray[] = [
                'id' => $course->getId(),
                'libelle' => $course->getLibelle(),
                'cheminImage' => $course->getCheminImage(),
            ];
        }

        return new JsonResponse([
            'cours' => $coursArray,
            'responsable' => $responsable ? $responsable->getNom() : null,  
        ]);
    }

    public function consulterCours(ManagerRegistry $doctrine, int $id){

        $user = $this->getUser();

        $responsable = $user->getResponsable();

		$cours= $doctrine->getRepository(Cours::class)->find($id);

		if (!$cours) {
			throw $this->createNotFoundException(
            'Aucun cours trouvé avec le numéro '.$id
			);
		}

		return $this->render('cours/consulter.html.twig', [
            'cours' => $cours,
            'responsable' => $responsable]);
	}

    public function ajouterCours(ManagerRegistry $doctrine,Request $request){
        $cours = new cours();
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
                $cours = $form->getData();
    
                $entityManager = $doctrine->getManager();
                $entityManager->persist($cours);
                $entityManager->flush();
    
            return $this->render('cours/consulter.html.twig', ['cours' => $cours,]);
        }
        else
            {
                return $this->render('cours/ajouter.html.twig', array('form' => $form->createView(),));
        }
    }

    public function supprimerCours(ManagerRegistry $doctrine, int $id): Response
    {
        $cours = $doctrine->getRepository(Cours::class)->find($id);

        if (!$cours) {
            throw $this->createNotFoundException('Aucun cours trouvé avec l\'ID '.$id);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->remove($cours); 
        $entityManager->flush();

        return $this->redirectToRoute('app_cours_lister');
    }

    public function modifierCours(ManagerRegistry $doctrine, $id, Request $request){
 
        $cours = $doctrine->getRepository(Cours::class)->find($id);
     
        if (!$cours) {
            throw $this->createNotFoundException('Aucun cours trouvé avec le numéro '.$id);
        }
        else
        {
                $form = $this->createForm(CoursModifierType::class, $cours);
                $form->handleRequest($request);
     
                if ($form->isSubmitted() && $form->isValid()) {
     
                     $cours = $form->getData();
                     $entityManager = $doctrine->getManager();
                     $entityManager->persist($cours);
                     $entityManager->flush();
                     return $this->render('cours/consulter.html.twig', ['cours' => $cours,]);
               }
               else{
                    return $this->render('cours/ajouter.html.twig', array('form' => $form->createView(),));
               }
            }
     }

     #[Route('/search/cours', name: 'search_cours', methods: ['GET'])]
     public function search(Request $request, ManagerRegistry $doctrine)
     {
         $searchTerm = $request->query->get('query');  // Récupérer le terme de recherche envoyé
     
         // Vérifier que le terme de recherche n'est pas vide
         if (empty($searchTerm)) {
             return new JsonResponse([
                 'error' => 'Le terme de recherche est vide.',
             ], Response::HTTP_BAD_REQUEST);
         }
     
         // Requête pour récupérer les cours qui correspondent à la recherche
         $cours = $doctrine->getRepository(Cours::class)
             ->createQueryBuilder('c')
             ->where('c.libelle LIKE :searchTerm')
             ->setParameter('searchTerm', '%' . $searchTerm . '%')
             ->setMaxResults(10)
             ->getQuery()
             ->getResult();
     
         // Préparer les résultats sous forme d'un tableau pour envoyer à JavaScript
         $coursArray = [];
         foreach ($cours as $course) {
             $coursArray[] = [
                 'id' => $course->getId(),
                 'libelle' => $course->getLibelle(),
                 'cheminImage' => $course->getCheminImage(),
             ];
         }
     
         // Retourner les résultats sous forme de JSON
         return new JsonResponse([
             'cours' => $coursArray,
         ]);
     }

}