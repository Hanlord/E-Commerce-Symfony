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
    public function index(ProductRepository $productRepository): Response
    {
        
        return $this->render('shopping_cart/index.html.twig', [
            'products' => $productRepository->findAll(),
            
        ]);
    }
    #[Route('/cart/{id}', name: 'app_product_add', methods: ['GET'])]
    public function addCart($id,User $fkuser, Product $product, CartRepository $cart, Request $request, ManagerRegistry $doctrine): Response
    {
        $fkuser = $this->getUser(User::class);
        $fkproduct = $doctrine->getRepository(Product::class)->find($id);
        $cartitem = $doctrine->getRepository(Cart::class);
        // dd($fkproduct);
        $shopcart = $cartitem->findBy(array('fk_user' => $fkuser, 'fk_product' => $fkproduct));    
        if(!$shopcart){
            $shopcart = new Cart();
            $shopcart->setFkUser($this->getUser());
            $shopcart->setFkProduct($product);
            $shopcart->setStatus("0");
            $now = new \DateTime("now");
            $shopcart->setDatetime($now);
            $cart->add($shopcart, true);
        }
        return $this->redirectToRoute('app_product_crud_index', ['id' => $id]);
    }
}
