<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Order;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'payment')]
    public function index(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }
        #[Route('/payment/{id}', name: 'app_payment')]
        public function payment(CartRepository $cart): Response
        {
            $userid = $this->getUser();
            $items = $cart->findBy(['fk_user' => $userid, 'fk_order' => NULL]);
            $total = 0;
            foreach($items as $value){
                $price = $value->getFkProduct()->getPrice();
                $discount = $value->getFkProduct()->getFkDiscount();
                $total += $price * ((100-$discount)/100);
            }

            return $this->render('payment/index.html.twig', [
                'total' => $total,
            ]);
        }


    #[Route('/success', name: 'app_payment_success')]
    public function success(CartRepository $cart, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $items = $cart->findBy(['fk_user' => $user, 'fk_order' => NULL]);
        $order = new Order;
        $total = 0;
        foreach($items as $value){
            $price = $value->getFkProduct()->getPrice();
            $discount = $value->getFkProduct()->getFkDiscount()->getAmount();
            $product = $value->getFkProduct();
            $total += $price * ((100-$discount)/100);
            $value->setFkOrder($order);
        }
        $entityManager->persist($order);
        $entityManager->flush();

        

        $this->addFlash('success', 'Thanks for payment.');
        return $this->redirectToRoute('app_test');
    }


    #[Route('/error', name: 'app_payment_error')]
    public function error(): Response
    {
        $this->addFlash('notice', 'Something went wrong.');
        return $this->redirectToRoute('app_shopping_cart');
    }
}
