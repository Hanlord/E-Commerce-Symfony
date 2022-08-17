<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Review;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\ReviewRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

// #[Route('/product/crud')]
class ProductCrudController extends AbstractController
{
    #[Route('/product/crud/', name: 'app_product_crud_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product_crud/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('admin/product/crud/new', name: 'app_product_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProductRepository $productRepository,FileUploader $fileUploader): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $image = $form->get('image')->getData();
            // $product->setImage($image);
            // $form->get('image')->setData(true);
            $pic = $form->get('image')->getData();
            if ($pic){
                $pictureFileName = $fileUploader->upload($pic);
                $product->setImage($pictureFileName);
              }
              
            $productRepository->add($product, true);

            return $this->redirectToRoute('app_product_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_crud/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/product/crud/{id}', name: 'app_product_crud_show', methods: ['GET', 'POST'])]
    public function show(Product $product,ProductRepository $productRepository, $id, Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        $defaultData = ['message' => 'Type your review here'];
        $form = $this->createFormBuilder($defaultData)
            ->add('title', TextType::class, [
                'label_attr'=>['class'=>'form-label'],
                'row_attr'=>['class'=>'col-12'],
                'attr' => ['class' => 'form-control mb-1', 'placeholder'=>'Title'] ])
            ->add('rating', ChoiceType::class, [
                'choices' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'],
                'label_attr'=>['class'=>'form-label'],
                'row_attr'=>['class'=>'col-12'],
                'attr' => ['class' => 'form-control mb-1']])
            ->add('message', TextareaType::class,[
                'label_attr'=>['class'=>'form-label'],
                'row_attr'=>['class'=>'col-12'],
                'attr' => ['class' => 'form-control mb-1'] ])
            ->add('send', SubmitType::class,[
                'row_attr'=>['class'=>'col-12 text-center mb-3'],
                'attr' => ['class' => 'btn btn-primary']
            ])
            ->getForm();
        $form->handleRequest($request);
        $fkproduct = $doctrine->getRepository(Product::class)->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
                $formdata = $form->getData();
                $fkuser = $this->getUser();
                $usertest = $doctrine->getRepository(Review::Class)->findBy(['fk_user' => $fkuser, 'fk_product' => $fkproduct]);
                if (sizeof($usertest) > 0) {
                    $review = $usertest[0];
                } else {
                    $review = new Review();
                    $review->setFkUser($fkuser);
                    $review->setFkProduct($fkproduct);
                }
                $review->setTitle($formdata["title"]);
                $review->setRating($formdata["rating"]);
                $review->setMessage($formdata["message"]);
                $entityManager->persist($review);
                $entityManager->flush();
            }
        $reviews = $doctrine->getRepository(Review::class)->findBy(['fk_product' => $fkproduct]);;
        // dd($reviews);
        $discount = $product->getFkDiscount();
        return $this->render('product_crud/show.html.twig', [
            'reviewform' => $form->createView(),
            'reviews' => $reviews,
            'product' => $product,
            'discount'=>$discount,
        ]);
    }

    #[Route('admin/product/crud/{id}/edit', name: 'app_product_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository,FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pic = $form->get('image')->getData();
            if ($pic){
                $pictureFileName = $fileUploader->upload($pic);
                $product->setImage($pictureFileName);
            }
            $productRepository->add($product, true);

            return $this->redirectToRoute('app_product_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_crud/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('admin/product/crud/{id}', name: 'app_product_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_product_crud_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/product/crud/category/{filter}', name: 'app_product_crud_filter')]
    public function filter(ManagerRegistry $doctrine, $filter): Response
    {
        if($filter=="all"){
        $category = $doctrine->getRepository(Product::class)->findAll();
        }
        else {
        $category = $doctrine->getRepository(Product::class)->findBy(['fk_category' => $filter]);
        }
        return $this->render('product_crud/index.html.twig', [
            'products' => $category
        ]);
    }
    #[Route('/search/', name: 'app_search')]
    public function search(ManagerRegistry $doctrine, Request $request): Response
    {
        $search = $request->query->get('search');
        $result = $doctrine->getRepository(Product::class)->findBy(array('name' => $search));
        
        if($result){
            return $this->render('product_crud/index.html.twig', ['products' => $result]);
        }else{
            $this->addFlash('notice', 'not found.');
            return $this->redirectToRoute('app_product_crud_index');
        }
    }
    #[Route('/product/crud/review/delete/{id}', name: 'app_product_delete_review')]
    public function deleteCart($id, ReviewRepository $review, ManagerRegistry $doctrine): Response
    {
        $reviewitem = $doctrine->getRepository(review::class)->find($id);
        $review->remove($reviewitem, true);
        return $this->redirectToRoute('app_product_crud_show', ['id' => $reviewitem->getFkProduct()->getId()]);
    }
}