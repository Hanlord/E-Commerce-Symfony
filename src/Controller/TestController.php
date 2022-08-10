<?php

namespace App\Controller;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
