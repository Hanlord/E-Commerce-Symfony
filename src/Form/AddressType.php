<?php

namespace App\Form;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class, [
                'label_attr'=>['class'=>'form-label'],
                'row_attr'=>['class'=>'col-12'],
                'attr' => ['class' => 'form-control mb-1', 'placeholder'=>'street, house number'] ])
            ->add('zip', TextType::class, [
                'label'=>'Post Code',
                'label_attr'=>['class'=>'form-label'],
                'row_attr'=>['class'=>'col-md-2'],
                'attr' => ['class' => 'form-control mb-1', 'placeholder'=>'1010'] ])
            ->add('city', TextType::class, [
                'label_attr'=>['class'=>'form-label'],
                'row_attr'=>['class'=>'col-md-4'],
                'attr' => ['class' => 'form-control mb-1', 'placeholder'=>'Vienna'] ])
            ->add('country', TextType::class, [
                'label_attr'=>['class'=>'form-label'],
                'row_attr'=>['class'=>'col-md-6'],
                'attr' => ['class' => 'form-control mb-1', 'placeholder'=>'Austria'] ])
            ->add('agreeTerms', CheckboxType::class, [
                'label'=>'Agree to our Terms & Conditions',
                'label_attr'=>['class'=>'form-check-label'],
                'row_attr'=>['class'=>'col-12 my-3'],
                'attr'=>['class'=>'form-check-input mx-3'],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
