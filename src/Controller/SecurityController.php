<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestFormType;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }
  // retourne à cet adress si l'utilisateur est déja connecté
        // get the login error if there is one



//         get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
//         last username entered by the user (preremplir avec le dernier nom utilisé
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/déconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/oubli-pass', name: 'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UsersRepository $usersRepository
        ): Response
        {
            $form = $this->createForm(ResetPasswordRequestFormType::class);
            // on traite le formulaire , 
            // je gere la requete avec le composant Request de httpfoundation
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                //on vz chercher l'utilisateur par son email
                $user = $usersRepository->findOneByEmail($form->get('email')->getData());
                
                //on verifie si on a un utilisateur
                if($user){

                }
                // si $user n'existe pas
                $this->addFlash('danger', 'Un problème est survenu');

                return $this->redirectToRoute('app_login');


            }

            return $this->render('security/reset_password_request.html.twig', [
                'requestPassForm' => $form->createView()
            ]);
        }
}
