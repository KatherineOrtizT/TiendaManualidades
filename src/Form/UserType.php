<?php

namespace App\Form;

use App\Entity\User;
use App\Validator\UniqueEmail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Unique;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class, [
                "label" => "Email",
                "required" => true,
                "constraints" => [
                    new NotBlank(),
                    new UniqueEmail(),
                ]
            ])
            ->add('password', PasswordType::class, [
                'hash_property_path' => 'password',
                'mapped' => false,
                "constraints" => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/',
                        'match'   => true,
                        'message' => 'La contraseña no es válida. Debe contener al menos 8 caracteres: un número, una letra mayúscula y una letra minúscula.'
                    ])
                ]
            ])
            ->add('photo', FileType::class, 
            [
                'label' => 'Imagen de perfil',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Por favor, sube una imagen con un formato adecuado.',
                    ])
                ],
            ])
            ->add('Nombre', TextType::class, [
                "label" => "Nombre",
                "required" => true,
                "constraints" => [
                    new NotBlank(),
                    new Length(['min' => 3]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'match'   => true,
                        'message' => 'Solo mayúsculas y minúsculas permitidos.'
                    ])
                ]
            ])
            ->add('Apellidos', TextType::class, [
                "label" => "Apellidos",
                "required" => true,
                "constraints" => [
                    new NotBlank(),
                    new Length(['min' => 3]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z]+$/',
                        'match'   => true,
                        'message' => 'Solo mayúsculas y minúsculas permitidos.'
                    ])
                ]
            ])
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
