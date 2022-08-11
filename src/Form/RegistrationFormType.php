<?php

namespace App\Form;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label_attr'=>['class'=>'form-label'],
                'row_attr'=>['class'=>'col-md-6'],
                'attr' => ['class' => 'form-control mb-1', 'placeholder'=>'username@email.com'] ])
            ->add('plainPassword', PasswordType::class, [
                'label'=>'Password',
                'label_attr'=>['class'=>'form-label'],
                'row_attr'=>['class'=>'col-md-6'],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'class'=>'form-control mb-1', 'placeholder'=>'**********'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('name', TextType::class, [
                'label_attr'=>['class'=>'form-label'],
                'row_attr'=>['class'=>'col-md-6'],
                'attr' => ['class' => 'form-control mb-1', 'placeholder'=>'first name'] ])
            ->add('surname', TextType::class, [
                'label_attr'=>['class'=>'form-label'],
                'row_attr'=>['class'=>'col-md-6'],
                'attr' => ['class' => 'form-control mb-1', 'placeholder'=>'last name'] ])
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
                'label_attr'=>['class'=>'form-label'],
                'row_attr'=>['class'=>'col-12'],
                'attr' => ['class'=>'form-control mb-1']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
