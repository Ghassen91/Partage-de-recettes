<?php

namespace App\Controller;

use App\Form\RecetteAddType;
use App\Repository\RecetteRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
class RecetteAddController extends AbstractController
{
    #[Route('/ajouter-recette', name: 'app_recette_add_new')]
    public function new(Request $request, RecetteRepository $repository): Response
    {
        //je crée le formulaire
        $form = $this->createForm(RecetteAddType::class);

        //je le rempli avec les données saisies
        $form->handleRequest($request);

        //je teste le formulaire
        if($form->isSubmitted() && $form->isValid()) {

            //je récupère la recette
            $recette = $form
                ->getData()
                ->setCreatedAt(new DateTime())
                ->setUpdatedAt(new DateTime())
            //je récupère l'utilisateur
                ->setUser($this->getUser());

                

            //j'enregistre dans la base de données
            $repository->save($recette, true);

            //@TODO On redirige vers la page de profil
            return new Response('OK');

        }
        //on affiche le formulaire
        return $this->render('recette_add/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}