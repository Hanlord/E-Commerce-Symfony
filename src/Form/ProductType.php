<?php

namespace App\Form;
use App\Entity\Discount;
use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control mb-1',  'style' => 'width:30%'] ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control mb-1',  'style' => 'width:30%'] ])
            ->add('price', IntegerType::class, [
                'attr' => ['class' => 'form-control mb-1',  'style' => 'width:30%'] ])

            ->add('image', FileType::class, [
                'label' => 'Upload Picture',
     
                'mapped' => false,
    
                'required' => false,
     
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
                'attr' => ['class'=>'form-control mb-1', 'style' => 'width:30%']
            ])

            ->add('availability', ChoiceType::class, [
                'choices' => ['available' => '1', 'not available' => '0'],
                'attr' => ['class' => 'form-select mb-1',  'style' => 'width:30%'] ])
            ->add('fk_category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label'=>'Category',
                'attr' => ['class' => 'form-select mb-1',  'style' => 'width:30%']
            ])
            ->add('fk_discount', EntityType::class, [
                'class' => Discount::class,
                'choice_label' => 'name',
                'label'=>'Discount',
                'attr' => ['class' => 'form-select mb-1',  'style' => 'width:30%']
            ])  
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
