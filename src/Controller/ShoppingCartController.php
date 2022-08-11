<?php

namespace App\Controller;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Cart;
use Symfony\Component\Form\AbstractType;
use App\Entity\Product;
use App\Form\AddToCartType;
use App\Manager\CartManager;
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('quantity');
        $builder->add('add', SubmitType::class, [
            'label' => 'Add to cart'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderItem::class,
        ]);
    }
    
    
}
