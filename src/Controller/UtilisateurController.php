<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\EditFormClientType;
use App\Form\EditProfileType;
use App\Form\LoginType;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


#[Route('/utilisateur')]
class UtilisateurController extends AbstractController
{
    private $passwordEncoder;
    // private SessionInterface $session;
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
    #[Route('/generate-pdf', name:"generate_pdf", methods: ['GET'])]
    public function generatePdf(UtilisateurRepository $utilisateurRepository): Response
    {
        // Créer une instance de Dompdf
        $dompdf = new Dompdf();

        // Générer le contenu HTML du PDF en utilisant le template Twig
        $html = $this->renderView('utilisateur/pdfList.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Rendre le PDF
        $dompdf->render();

        // Retourner une réponse avec le contenu du PDF
        return new Response($dompdf->output(), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="listUsers.pdf"',
        ]);
    }

    
    #[Route('/list', name:"listUtilisateur", methods: ['GET'])]
    // #[Security("app.session.get('profil') == 0")]
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
    #[Route('/blocked', name: 'blockRedirection')]
    public function blockRedirection ():Response{
        return $this->render('utilisateur/blockedPage.html.twig', [        
        ]);
        
    }
    // --------------------------------------------------------------------
    #[Route('/editprofileclient', name: 'editprofileclient')]
    public function editprofileclient ():Response{
        return $this->render('utilisateur/editProfileClient.html.twig', [        
        ]);
        
    }
    #[Route('/{id}/editFormClient', name: 'editFormClient', methods: ['GET', 'POST'])]
    public function editFormClient(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(EditProfileType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $file = $form->get('image')->getData();
            // Vérifier si une nouvelle image a été téléchargée
            if ($file) {
                $fileName = uniqid().'.'.$file->guessExtension();
                $file->move($this->getParameter('images_directorys'), $fileName);
                $utilisateur->setImage($fileName);
            }
              else {
            // Récupérer l'image existante de l'entité Utilisateur
            $imageExistante = $utilisateur->getImage();

            // Si une image existante est présente, la conserver
            if ($imageExistante) {
                $utilisateur->setImage($imageExistante);
            }
        }
            $entityManager->flush();
        }

        return $this->renderForm('utilisateur/editFormClient.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }
    // --------------------------------------------------------------------
       #[Route('/login', name : "login_user")]
    public function login (UtilisateurRepository $userRepo ,Request $request , SessionInterface $session ): Response{
        $user = new Utilisateur();
        $form1 = $this->createForm(LoginType::class, $user);
        $form1->handleRequest($request);

        if ($form1->isSubmitted() && $form1->isValid()){
            $login = $user->getLogin();
            $mdp = $user->getMdp();
            $result = $userRepo->login($login, $mdp);
            if ($result instanceof Utilisateur) {

           $session ->set('user_id', $result->getId()) ;
           $session->set('user_image', $result->getImage()) ;
           $session->set('user_nom', $result->getNom()) ;
           $session->set('user_prenom', $result->getPrenom()) ;
           $session->set('user_profil', $result->getProfil()) ;
           $session->set('userConnected', $result) ;}   

            return $this->render('utilisateur/login.html.twig',[
                'user' => $result,
                'form1' => $form1->createView(),
            ]) ;
        } else {
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
   if ($file instanceof UploadedFile) {
            $fileName = uniqid().'.'.$file->guessExtension();
            $file->move($this->getParameter('images_directorys'), $fileName);
            $utilisateur->setImage($fileName);
        } else {
                $utilisateur->setImage($utilisateur->getImage());
            
        }
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
    #[Route('/blockDeblock/{id}', name:"utilisateurBlockage")]
    public function utilisateurBlockage (ManagerRegistry $doctrine , $id, UtilisateurRepository $utilisateurRepo) : Response {
        $em = $doctrine->getManager();
        $utilisateur= $utilisateurRepo->find($id);
        $checkState = $utilisateur->getEtatCompte() ;
        if ($checkState == 0) {
            $utilisateur->setEtatCompte(1);
        } elseif ($checkState == 1) { 
            $utilisateur->setEtatCompte(0);

        }

        //$em->remove($utilisateurDel);
        $em->flush();

        return $this->redirectToRoute("listUtilisateur");
    }

}
