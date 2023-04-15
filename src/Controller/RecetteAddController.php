<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Recette;
use App\Form\RecetteAddType;
use App\Repository\RecetteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[IsGranted('ROLE_USER')]
class RecetteAddController extends AbstractController
{

    /**
     * Permet de créer une recette
     */
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

            //On redirige vers la page de profil
            return $this->redirectToRoute('app_profile_show');

        }
        //on affiche le formulaire
        return $this->render('recette_add/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * Permet de modifier une recette
     */
    #[Route('/recette/{id}/modifier', name: 'app_recette_add_update')]
    public function update(Recette $recette, RecetteRepository $repository, Request $request):Response
    {
        //Je récupère l'utilisateur connecter
        /**
         * @var User
         */

        $user = $this->getUser();

        //Je vérifie que la recette appartienne bien a l'utilisateur
        if($recette->getUser()->getId() !== $user->getId()){
            throw new NotFoundHttpException('');
        }

        //création du formulaire
        $form = $this->createForm(RecetteAddType::class, $recette);

        //on rempli le formulaire
        $form->handleRequest($request);

        //on teste la validité du formulaire
        if($form->isSubmitted() && $form->isValid()){
            //on récupère la recette
            $recette = $form
                ->getData()
                ->setUpdatedAt(new DateTime);

            //on enregistre la recette dans la BD
            $repository->save($recette, true);

            //On redirige vers la page de profil
            return $this->redirectToRoute('app_profile_show');
        }
        
        //On affiche le formulaire
        return $this->render('recette_add/update.html.twig', [
            'form' => $form->createView(),
            'recette' => $recette
        ]);
    }

    /**
     * Permet de supprimer une recette
     */
    #[Route('/recette/{id}/supprimer', name: 'app_recette_add_remove')]
    public function remove(Recette $recette, RecetteRepository $repository):Response
    {
        //Je récupère l'utilisateur connecter
        /**
         * @var User
         */

        $user = $this->getUser();

        //Je vérifie que la recette appartienne bien a l'utilisateur
        if($recette->getUser()->getId() !== $user->getId()){
            throw new NotFoundHttpException('');
        }

        //On supprime la recette de la BD
        $repository->remove($recette, true);

            //On redirige vers la page de profil
            return $this->redirectToRoute('app_profile_show');
        }
    


}