<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\EditFormClientType;
use App\Form\EditProfileType;
use App\Form\ForgetPWDType;
use App\Form\LoginType;
use App\Form\ModifierMDPType;
use App\Form\UtilisateurType;
use App\Form\VerifySendedCodeType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
// use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Label\Font\NotoSans;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Controller\PanierController;
use App\Entity\Panier;
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

    #[Route('/qr-codes', name: 'app_qr_codes')]
    public function indexQR(SessionInterface $session): Response
    {
        // $path = "oeuvre/".$session->get('user_image');
        $userConnected = $session->get('userConnected');
        $username = $userConnected ? $userConnected->getNom() : null;
        $prenom =  $userConnected ? $userConnected->getPrenom() : null;
        $email =  $userConnected ? $userConnected->getEmail() : null;
        $adresse =  $userConnected ? $userConnected->getAdresse() : null;
        $numTel =  $userConnected ? $userConnected->getNumtel() : null;
        $cin =  $userConnected ? $userConnected->getCin() : null;
        $DateInscription =  $userConnected ? $userConnected->getDateInscription() : null;
        $getDatenaissance =  $userConnected ? $userConnected->getDatenaissance() : null;

    $userData = "Nom et Prenom : " . $username . "  " .$prenom . " // " .
            "Email : " . $email . " // ".
            "Numero de Telephone : " . $numTel.  " // ".
            "Adresse : " . $adresse.  " // ".
            "CIN : " . $cin.  " // ";

     $writer = new PngWriter();
        // Créer un objet QrCode de base
        $qrCodeBase = QrCode::create(json_encode($userData))
            ->setEncoding(new Encoding('UTF-8'))
            ->setSize(450)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        // Créer le label
        $label = Label::create('')->setFont(new NotoSans(8));
        $qrCodes = [];

        // Générer les codes QR et stocker les URI dans le tableau
        $qrCodes['simple'] = $writer->write($qrCodeBase, null, $label->setText('Simple'))->getDataUri();
        // Retourner le tableau des URI des codes QR
        return $this->render('utilisateur/qr.html.twig', $qrCodes);

    }


    #[Route('/list', name:"listUtilisateur", methods: ['GET'])]
    // #[Security("app.session.get('profil') == 0")]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }
    #[Route('/modifierMDP', name: 'modifierMDP')]
public function resetPage(UtilisateurRepository $userRepo ,Request $request, MailerInterface $mailer, SessionInterface $session , EntityManagerInterface $entityManager){
    $form = $this->createForm(ModifierMDPType::class);
    $form->handleRequest($request);
    $utilisateur = new Utilisateur();
    $utilisateur = $userRepo->findOneBy(['email' => $session->get('reset_password_email')]) ;
    if ($form->isSubmitted() && $form->isValid()){
            $plainPassword = $form->get('mdp')->getData(); ;
            $hashedPassword = $this->hashPassword($plainPassword);
            $utilisateur->setMdp($hashedPassword);
            $entityManager->persist($utilisateur);
            $entityManager->flush();

             return $this->render('utilisateur/modifierMDP.html.twig',[
                'success' => "success",
                'form' => $form->createView(),
            ]) ;
        
        } else {
                return $this->render('utilisateur/modifierMDP.html.twig',[
                    'form' => $form->createView(),
                    'success'=> "Code Invalide",
            ]) ;
            }
}
    

#[Route('/verifySendedCode', name: 'verifySendedCode')]
public function verifySendedCode(Request $request){
        
    $form = $this->createForm(VerifySendedCodeType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()){
        $enteredCode = $form->get('code')->getData();
        $sessionCode = $request->getSession()->get('reset_password_code');
        if ($enteredCode == $sessionCode) {           
            return $this->redirectToRoute('modifierMDP');
        } else {
             return $this->render('utilisateur/verifySendedCode.html.twig',[
                'error' => "Code Invalide",
                'form' => $form->createView(),
            ]) ;
        }
        } else {
                return $this->render('utilisateur/verifySendedCode.html.twig',[
                    'form' => $form->createView(),
                    'error'=> "Code Invalide",
            ]) ;
            }
}
    #[Route('/forgetPwd', name: 'forget_password')]
    public function forgetPwd (UtilisateurRepository $userRepo ,Request $request, MailerInterface $mailer, SessionInterface $session):Response{
        
        $user = new Utilisateur();
        $form = $this->createForm(ForgetPWDType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $email = $user->getEmail();
            $result = $userRepo->verifEmail($email);
           
            if ($result instanceof Utilisateur) {
            $code = mt_rand(100000, 999999);
            $message = (new Email())
                ->from('artvuecontact@gmail.com')
                ->to($email)
                ->subject('Code de réinitialisation du mot de passe')
                ->html("<p>Votre code de réinitialisation du mot de passe est : $code</p>");

            $mailer->send($message);

            // Enregistrez le code de vérification et l'email dans la session
            $session->set('reset_password_email', $email);
            $session->set('reset_password_code', $code);

            // Redirigez l'utilisateur vers la page de réinitialisation du mot de passe
            return $this->redirectToRoute('verifySendedCode');
            }
             return $this->render('utilisateur/forgetPwd.html.twig',[
                'user' => $result,
                'form' => $form->createView(),
            ]) ;
            
        } else {
                return $this->render('utilisateur/forgetPwd.html.twig',[
                    'form' => $form->createView(),
                    'user'=> "no user found !!!",
            ]) ;
            }
    
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
    public function editFormClient(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $userId = $session->get('user_id');
        
        // Fetch the Utilisateur entity from the database
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($userId);
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

            $panier = new Panier(); // Appel du constructeur pour initialiser la date d'ajout
            $panier->setDateajout(new \DateTime());
            $panier->setClient($utilisateur);
            $entityManager->persist($panier);

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
    public function show(Utilisateur $utilisateur,EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $userId = $session->get('user_id');
        
        // Fetch the Utilisateur entity from the database
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($userId);
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager,SessionInterface $session): Response
    {
        $userId = $session->get('user_id');
        
        // Fetch the Utilisateur entity from the database
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($userId);

        

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