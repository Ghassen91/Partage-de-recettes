<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/inscription', name: 'app_user_signUp')]
    public function signUp(Request $request, UserRepository $repository, UserPasswordHasherInterface $encoder): Response
    {
        //Je crée le formulaire
        $form = $this->createForm(UserType::class);

        //je rempli le form avec les données saisies
        $form->handleRequest($request);

        //Je teste le formulaire
        if($form->isSubmitted() && $form->isValid()) {

            //on vérifie si l'utilisateur existe
            $existingUser = $repository->findOneBy(['email' => $form->getData()->getEmail()]);

            if($existingUser){
                // si l'utilisateur existe deja retourner une erreur
                return $this->render('user/signUp.html.twig', [
                    'error' => 'Un utilisateur avec cet email existe deja'
                ]);
            }
            

            //je récupère l'utilisateur
            $user = $form
                ->getData()
                //on spécifie les dates
                ->setCreatedAt(new DateTime())
                ->setUpdatedAt(new DateTime())
                //on crypte le mot de passe
                ->setPassword($encoder->hashPassword(
                    $form->getData(),
                    $form->getData()->getPassword()
                ));

            //j'enregistre dans la base de données
            $repository->save($user, true);

            //je redirige vers la page de connexion
            return $this->redirectToRoute('app_login');

        }

        //j'affiche le formulaire
        return $this->render('user/signUp.html.twig', [
            'form' => $form->createView(),
        
        ]);
    }

    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}