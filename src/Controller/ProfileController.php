<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function profileEdit(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
}
