<?php

namespace App\Form;
use App\Entity\User;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Security;
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
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationFormType extends AbstractType
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label_attr'=>['class'=>'form-label'],
                'row_attr'=>['class'=>'col-md-6'],
                'attr' => ['class' => 'form-control mb-1', 'placeholder'=>'username@email.com'] ]);
            if ($this->security->getUser() == false) {
                $builder->add('plainPassword', PasswordType::class, [
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
                    'required' => false
                ]);
            }
        $builder
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
                'row_attr'=>['class'=>'col-md-6'],
                'attr' => ['class'=>'form-control mb-1']
            ]);
            if ($this->security->isGranted('ROLE_ADMIN')) {
                $builder
                ->add('status', TextType::class,[
                'label_attr'=>['class'=>'form-label'],
                'row_attr'=>['class'=>'col-md-6'],
                'attr' => ['class'=>'form-control mb-1']])
                ->add('roles', CollectionType::class , ['row_attr'=>['class'=>'col-12'],
                'label_attr'=>['class'=>'form-label m-0 p-0'],
                "entry_type"=> ChoiceType::class, 'entry_options' => [
                'label'=>' ',
                'attr' => ['class' => 'form-select mb-1'],
                "choices"=> ["admin"=>"ROLE_ADMIN", "user"=>"ROLE_USER"]
                        ]]);
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
