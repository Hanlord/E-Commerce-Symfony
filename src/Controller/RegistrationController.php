<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Form\RegistrationFormType;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Service\FileUploader;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Doctrine\Persistence\ManagerRegistry;


class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,ManagerRegistry $doctrine, FileUploader $fileUploader): Response
    {
        $user = new User();
        $address = new Address();
        $formAddress = $this->createForm(AddressType::class, $address);
        $form = $this->createForm(RegistrationFormType::class, $user);
        
        $form->handleRequest($request);
        $formAddress->handleRequest($request);
        $userStatus = "Good";
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $address = $formAddress->getData();
            $pic = $form->get('image')->getData();
            if ($pic){
                $pictureFileName = $fileUploader->upload($pic);
                $user->setImage($pictureFileName);
              }
            $user->setFkAddress($address);
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                ),
            $user->setStatus($userStatus)
            );
            $entityManager->persist($address);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'addressForm' => $formAddress->createView(),
        ]);
    }
}
