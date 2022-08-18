<?php

namespace App\Controller;
use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Shipment;
use App\Entity\Discount;
use App\Entity\User;
use App\Repository\ProductRepository;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Annotation\Route;

class ShoppingCartController extends AbstractController
{
    #[Route('/shopping/cart', name: 'app_shopping_cart')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $fkuser = $this->getUser();
        $cartitem = $doctrine->getRepository(Cart::class);
        $shopcart = $cartitem->findBy(array('fk_user' => $fkuser, 'status' => "0",'fk_order'=> NULL));
        $total = 0;
        foreach($shopcart as $val){
            $price = $val->getFkProduct()->getPrice();
            $discount = $val->getFkProduct()->getFkDiscount()->getAmount();
            $total += $price - ($price * ($discount/100));
        }   
        return $this->render('shopping_cart/index.html.twig', [
            'products' => $shopcart,
            'total' => $total,
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
    #[Route('/shopping/cart/delete/{id}', name: 'app_product_delete_cart')]
    public function deleteCart($id, CartRepository $cart, ManagerRegistry $doctrine): Response
    {
        $cartitem = $doctrine->getRepository(Cart::class)->find($id);
        $cart->remove($cartitem, true);
        return $this->redirectToRoute('app_shopping_cart');
    }
    #[Route('/shopping/order', name: 'app_add_order')]
    public function addOrder(OrderRepository $orderRep, CartRepository $cartRep, ManagerRegistry $doctrine): Response
    {
        $fkuser = $this->getUser();
        $order = new Order();
        $shipment = $doctrine->getRepository(Shipment::class)->find(1);
        $order->setFkShipment($shipment);
        $now = new \DateTime("now");
        $order->setOrderDate($now);
        $order->setItems("stuff");
        $order->setQuantity(1);
        $cartitems = $doctrine->getRepository(Cart::class)->findBy(array('fk_user' => $fkuser, 'status' => "0"));
        foreach ($cartitems as $items => $item) {
            $item->setStatus("ordered");
            $cartRep->add($item, true);
        }
        $orderRep->add($order, true);
        
        return $this->redirectToRoute('app_product_crud_index');
    }
    #[Route('/shopping/cart/price', name: 'app_cart_price', methods: ['GET'])]
    public function total(CartRepository $cart, Discount $discount): Response
    {
        $userid = $this->getUser();
        $products = $cart->findBy(['fk_user' => $userid, 'fk_order' => NULL]);
        $total = 0;
        foreach($products as $val){
            $price = $val->getFkProduct()->getPrice();
            $discount = $val->getFkProduct()->getFkDiscount();
            $total += $price * ((100-$discount)/100);
        }

        return $this->render('app_shopping_cart', [
            'products' => $products,
            'total' => $total,
        ]);


    }
}
