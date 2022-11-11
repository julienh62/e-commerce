<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UsersRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
// entitymanager va permetre de flush et persis dans la bdd
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
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
        UsersRepository $usersRepository,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,
        SendMailService $mail
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
                //on genere un token de réutilisation 
                // on aurait pu utiliser le m^me token que pour l'inscription
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                //possibilté de faire securité et control  avec try catch au cas où flush 
                //et persist ne fonctionne pas
                $entityManager->flush();

                //on genere un url de reinitialisation (ici grace à abstractcontroller)
                //lien que je vais recevoir par mail
                $url = $this->generateUrl('reset_pass' , ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL);
                //dd($url);

                //on crée les données du mail
                $context = compact('url', 'user');

                //Envoi du mail
                $mail->send(
                    'no-reply@e-commerce.fr',
                    $user->getEmail(),
                    'Réinitialisation du mot de passe',
                    'password_reset',
                    $context
                );

                $this->addFlash('success', 'Email envoyé avec succés');
                return $this->redirectToRoute('app_login');
            }
                // si $user n'existe pas
                $this->addFlash('danger', 'Un problème est survenu');
                return $this->redirectToRoute('app_login');


            }

            return $this->render('security/reset_password_request.html.twig', [
                'requestPassForm' => $form->createView()
            ]);
        }

        #[Route('/oubli-pass/{token}', name:'reset_pass')]
        public function resetPass(
            string $token,
            Request $request,
            UsersRepository $usersRepository,
            EntityManagerInterface $entitymanager,
            UserPasswordHasherInterface $passwordHasher
            ): Response
        {
         // on verifie que l'on a ce token dans la bdd
         $user = $usersRepository->findOneByResetToken($token);
         
         if($user){
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                //on efface le token
                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                    );
                    $entitymanager->persist($user);
                    $entitymanager->flush();

                    $this->addFlash('success', 'Mot de passe changé avec succès');
                    return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'passForm' => $form->createView()
            ]);
         }
          // si jeton n'est pas valide
          $this->addFlash('danger', 'Jeton invalide');
          return $this->redirectToRoute('app_login');
        }
}
