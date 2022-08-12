<?php

namespace App\Controller;
use App\Service\FileUploader;
use App\Entity\User;
use App\Entity\Address;
use App\Form\RegistrationFormType;
use App\Form\AddressType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'app_profile')]
    public function profileIndex(ManagerRegistry $doctrine, $id): Response
    {
        $user = $doctrine->getRepository(User::class)->find($id);
        $fkaddress = $user->getFkAddress();
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'address' => $fkaddress,
        ]);
    }
    #[Route('/profile/{id}/edit', name: 'app_profile_edit')]
    public function profileEdit(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,ManagerRegistry $doctrine, FileUploader $fileUploader, $id, User $user ): Response
        {
            $user = $doctrine->getRepository(User::class)->find($id);
            $address = $user->getFkAddress();
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
                $roles = $form->get('roles')->getData();
                // dd($roles);
                $user->setRoles($roles);
                // encode the plain password
                if ($this->getUser() == false) {
                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        ),
                    );
                }
                $user->setStatus($userStatus);
                $entityManager->persist($address);
                $entityManager->persist($user);
                $entityManager->flush();
                // do anything else you need here, like send an email
    
                return $this->redirectToRoute('app_login');
            }
            return $this->render('profile/edit.html.twig', [
                'controller_name' => 'ProfileController',
                'registrationForm' => $form->createView(),
                    'addressForm' => $formAddress->createView(),
                    'user' => $user,
                    'address' => $address
            ]);
            
        }
        #[Route('/delete/{id}', name: 'app_profile_delete')]
        public function delete($id, ManagerRegistry $doctrine): Response
        {
          $user = $doctrine->getRepository(User::class)->find($id);
          $address = $user->getFkAddress();
          $em = $doctrine->getManager();
          $em->remove($user);
          $em->remove($address);
          $em->flush();
        //   $this->addFlash("success", "user has been removed successfully");
          return $this->redirectToRoute('app_login');
        }
    }
