<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/product/crud')]
class ProductCrudController extends AbstractController
{
    #[Route('/', name: 'app_product_crud_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product_crud/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_product_crud_new', methods: ['GET', 'POST'])]
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

              
            if($form->get('image')->getData()==null){
                $image = 'https://cdn.pixabay.com/photo/2015/11/19/21/10/glasses-1052010_1280.jpg';
                $product->setImage($image);
            }
            $productRepository->add($product, true);

            return $this->redirectToRoute('app_product_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_crud/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_crud_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('product_crud/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productRepository->add($product, true);

            return $this->redirectToRoute('app_product_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_crud/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_product_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_product_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
