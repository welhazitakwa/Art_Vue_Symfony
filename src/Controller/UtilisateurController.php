<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\EditProfileType;
use App\Form\LoginType;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

     public function hashPassword($plainPassword)
    {
        // Générez un sel aléatoire
        $salt = substr(str_replace('+', '.', base64_encode(random_bytes(16))), 0, 22);
        
        // Générer le hachage avec le sel généré
        $hashedPassword = crypt($plainPassword, '$2a$10$' . $salt);
        
        return $hashedPassword;
    }

    #[Route('/list', name:"listUtilisateur", methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/forgetPwd', name: 'forget_password')]
    public function forgetPwd ():Response{
        return $this->render('utilisateur/forgetPwd.html.twig', [        
        ]);
        
    }
    #[Route('/editprofileclient', name: 'editprofileclient')]
    public function editprofileclient ():Response{
        return $this->render('utilisateur/editProfileClient.html.twig', [        
        ]);
        
    }

       #[Route('/login', name : "login_user")]
    public function login (UtilisateurRepository $userRepo ,Request $request , SessionInterface $session ): Response{
        $user = new Utilisateur();
        $form1 = $this->createForm(LoginType::class, $user);
        $form1->handleRequest($request);

        if ($form1->isSubmitted()){
            $login = $user->getLogin();
            $mdp = $user->getMdp();
            $result = $userRepo->login($login, $mdp);
            $session ->set('user_id', $result->getId()) ;
           $session->set('user_image', $result->getImage()) ;
           $session->set('user_nom', $result->getNom()) ;
           $session->set('user_prenom', $result->getPrenom()) ;
           $session->set('user_profil', $result->getProfil()) ;
           $session->set('userConnected', $result) ;

            return $this->render('utilisateur/login.html.twig',[
                'user' => $result,
                'form1' => $form1->createView(),
            ]) ;
        } else {
                // return $this-> redirectToRoute('listBooks');
                return $this->render('utilisateur/login.html.twig',[
                                    'form1' => $form1->createView(),

                'user'=> "no user found !!!",
            ]) ;
            }
    }
 
    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $defaultImagePath = $this->getParameter('kernel.project_dir').'/public/oeuvre/userimg.png';
             $uploadsDirectory = $this->getParameter('images_directorys');
             $newFilename = uniqid().'.'.pathinfo($defaultImagePath, PATHINFO_EXTENSION);
             copy($defaultImagePath, $uploadsDirectory.'/'.$newFilename);
            // Enregistrez le nom de fichier de l'image par défaut dans l'entité utilisateur
            $utilisateur->setImage($newFilename);

            $plainPassword =$utilisateur->getMdp() ;
            $hashedPassword = $this->hashPassword($plainPassword);
            $utilisateur->setMdp($hashedPassword);
            $entityManager->persist($utilisateur);
            $entityManager->flush();

           // return $this->redirectToRoute('listUtilisateur', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(EditProfileType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();
            $fileName = uniqid().'.'.$file->guessExtension();
            $file->move($this->getParameter('images_directorys'), $fileName);
            $utilisateur->setImage($fileName);
            $entityManager->flush();
            // return $this->redirectToRoute('listUtilisateur', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name:"utilisateurDelete")]
    public function deleteUtilisateur (ManagerRegistry $doctrine , $id, UtilisateurRepository $utilisateurRepo) : Response {
        $em = $doctrine->getManager();
        $utilisateurDel= $utilisateurRepo->find($id);
        $em->remove($utilisateurDel);
        $em->flush();

        return $this->redirectToRoute("listUtilisateur");
    }

}
