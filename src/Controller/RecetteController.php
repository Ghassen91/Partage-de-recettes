<?php

namespace App\Controller;

use DateTime;
use App\Entity\Recette;
use App\Form\CommentaireType;
use App\Repository\RecetteRepository;
use App\Repository\CommentaireRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecetteController extends AbstractController
{
    #[Route('/recette/{id}', name: 'app_recette_show')]
    public function show(Recette $recette, RecetteRepository $recetteRepository, Request $request, CommentaireRepository $commentaireRepository): Response
    {
        

        //je crée le formulaire
        $form = $this->createForm(CommentaireType::class);

        //je le rempli avec les données saisies
        $form->handleRequest($request);

        //je teste le formulaire
        if($form->isSubmitted() && $form->isValid()){
            //je récupère le commentaire
            $comment = $form
                ->getData()
                ->setCreatedAt(new DateTime())
                ->setUpdatedAt(new DateTime())
                //on récupère l'utilisateur
                ->setUser($this->getUser())
                //Je recupère la recette en cours
                ->setRecette($recette);

            //j'enregistre le commentaire en BD
            $commentaireRepository->save($comment, true);

            //je recrée un formualire pour avoir un nouveau champs vide
            $form = $this->createForm(CommentaireType::class);

        }

        
        //on récupère la recette
        $recette = $recetteRepository->find($recette);
        

        return $this->render('recette/show.html.twig', [
            'recette' => $recette,
            'form' => $form->createView()
        ]);
    }

}