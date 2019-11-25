<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class, [
                'label' => 'Ton pseudo',
            ])
            ->add('password', PasswordType::class,[
                'label' => 'Mot de passe'
            ])
            ->add('email')
            ->add('avatar', FileType::class, [
                'label' => 'Ton avatar',               
                'mapped' => false,               
                'required' => false,                
                'constraints' => [
                    new File([
                        'maxSize' => '500k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif'],
                        'mimeTypesMessage' => 'Seuls les format .jpg, .png et .gif sont acceptés',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
