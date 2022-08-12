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
use Symfony\Component\Form\AbstractType;
use App\Entity\Product;
use App\Form\AddToCartType;
use App\Manager\CartManager;
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
    public function addCart($id, Product $product, CartRepository $cart, Request $request): Response
    {
        $amount = $request->query->get('availability');
        $stock = $product->getAvailability();
       
        if($stock - $amount < 0){
            $this->addFlash('notice', 'Ordering amount is bigger than stock.');
            return $this->redirectToRoute('app_product_show', ['id' => $id]);
        }
        else{
            $userid = $this->getUser();
            $shopcart = $cart->findOneBy(array('fkUser' => $userid, 'fkProduct' => $id, 'fkOrder' => NULL));
            
            if($shopcart){
                $oldamount = $cart->getAmount();
                $newamount = $oldamount + $amount;
                if($stock - $newamount < 0){
                    $this->addFlash('notice', 'The new total ordering amount would be bigger than stock.');
                    return $this->redirectToRoute('app_product_show', ['id' => $id]);
                }
                else{
                    $cart->setAmount($newamount);
                    $cart->add($shopcart);
                }
            }
            else{
                $shopcart = new Cart();
                $shopcart->setFkUser($this->getUser());
                $shopcart->setFkProduct($product);
              
                $cart->add($shopcart);
            }

            return $this->redirectToRoute('app_product_index', ['id' => $id]);
        }


    }
    
}
