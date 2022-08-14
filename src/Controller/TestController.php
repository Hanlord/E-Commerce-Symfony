<?php

namespace App\Controller;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use stdClass;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class TestController extends AbstractController
{
    #[Route('/', name: 'app_test')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        return $this->render('test/index.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/test/{id}', name: 'app_test_link')]
    public function profile(ManagerRegistry $doctrine, $id): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);
        return $this->render('test/link.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function dashbord(ManagerRegistry $doctrine): Response
    {
        $users = $doctrine->getRepository(User::class)->findAll();
        return $this->render('test/dashboard.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/admin/dashboard/ban/{id}', name: 'app_admin_dashboard_ban')]
    public function dashbordBan(ManagerRegistry $doctrine, $id,  EntityManagerInterface $entityManager): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);
        $user->setStatus("ban");
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_dashboard');
    }
    #[Route('/admin/dashboard/unban/{id}', name: 'app_admin_dashboard_unban')]
    public function dashbordUnban(ManagerRegistry $doctrine, $id,  EntityManagerInterface $entityManager): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);
        $user->setStatus("Good");
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_dashboard');
    }
    // ***** CONTACT *****
    // INFO: Route is set intetionaly to '/temp/test/contact' for testing (it has to be fixed!)
    //        otherwise without '/temp' ther will be an error!
    #[Route('/temp/test/contact', name: 'app_test_contact', methods: ['GET', 'POST'])]
    public function contact(Request $request): Response
    {
        $contact = new stdClass();
        $contact->sent = false;
        $contact->name = "";
        $contact->email = "";
        $contact->mail_to = "example@mail.com";
        $contact->message = "";

        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
            ->add('name', TextType::class, [
            'label_attr'=>['class'=>'form-label'],
            'row_attr'=>['class'=>'col-12'],
            'attr' => ['class' => 'form-control mb-1', 'placeholder'=>'your name'] ])
            ->add('email', EmailType::class, [
                'label_attr'=>['class'=>'form-label'],
                'row_attr'=>['class'=>'col-12'],
                'attr' => ['class' => 'form-control mb-1', 'placeholder'=>'username@email.com']])
            ->add('message', TextareaType::class,[
            'label_attr'=>['class'=>'form-label'],
            'row_attr'=>['class'=>'col-12'],
            'attr' => ['class' => 'form-control mb-1'] ])
            ->add('send', SubmitType::class,[
            'row_attr'=>['class'=>'col-12 text-center mb-3'],
            'attr' => ['class' => 'btn btn-primary']
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $contact->sent = true;
            $contact->name = $data["name"];
            $contact->email = $data["email"];
            $contact->message = $data["message"];

            // *** INFO: We do not realy send an email, instead, we show a success Message/result below the form ***        
            // $headers = "FROM: ". $cls->email . "\r\n";
            // $mail_to = "example@mail.com";
            // if(mail($mail_to, "message coming from the contact form", $cls->msg, $headers)){
            //         echo "sent";
            // }else {
            //         echo "error";
            // }
        }

        return $this->render('test/contact.html.twig', [
            'contactform' => $form->createView(),
            'data' => $contact,    
        ]);
    }
    
}
