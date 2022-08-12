<?php

namespace App\Controller;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use App\Repository\CartRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Cart;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use App\Entity\Product;
use App\Form\AddToCartType;
use App\Manager\CartManager;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShoppingCartController extends AbstractController
{
    #[Route('/shopping/cart', name: 'app_shopping_cart')]
    public function index(ProductRepository $productRepository, ManagerRegistry $doctrine): Response
    {
        $fkuser = $this->getUser();
        $cartitem = $doctrine->getRepository(Cart::class);
        $shopcart = $cartitem->findBy(array('fk_user' => $fkuser, 'status' => "0"));
        return $this->render('shopping_cart/index.html.twig', [
            'products' => $shopcart,
        ]);
    }
    #[Route('/shopping/cart/{id}', name: 'app_product_add_cart', methods: ['GET'])]
    public function addCart($id, CartRepository $cart, ManagerRegistry $doctrine): Response
    {
        $fkproduct = $doctrine->getRepository(Product::class)->find($id);
        $shopcart = new Cart();
        $shopcart->setFkUser($this->getUser());
        $shopcart->setFkProduct($fkproduct);
        $shopcart->setStatus("0");
        $now = new \DateTime("now");
        $shopcart->setDatetime($now);
        $cart->add($shopcart, true);
        return $this->redirectToRoute('app_product_crud_index');
    }
}
