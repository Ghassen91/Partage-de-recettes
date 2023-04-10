<?php

namespace App\Controller;

use App\Form\RecetteSearchType;
use App\Repository\RecetteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Page d'accueil
     */
    #[Route('/', name: 'app_home_home')]
    public function home(RecetteRepository $repository): Response
    {
        //je récupère les 25 dernières recettes
        $recettes = $repository->findLatest();

        return $this->render('home/home.html.twig', [
            'recettes' => $recettes,
        ]);
    }


    /**
     * Recherche par critères
     */
    #[Route('/rechercher', name: 'app_home_search')]
    public function search(Request $request, RecetteRepository $repository): Response
    {
        //je crée le formulaire de recherche
        $form = $this->createForm(RecetteSearchType::class);

        //je rempli le formulaire avec les données saisies
        $form->handleRequest($request);

        //je lance la recherche
        $recettes = $repository->findAllByCriteria($form->getData());


        //j'affiche le formulaire de recherche
        return $this->render('home/search.html.twig', [
            'form' => $form->createView(),
            'recettes' => $recettes,
        ]);
    }
}