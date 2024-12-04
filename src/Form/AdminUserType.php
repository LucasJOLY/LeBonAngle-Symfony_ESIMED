<?php

namespace App\Form;

use App\Entity\AdminUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez un nom d\'utilisateur',
                ],
                'label' => 'Nom d\'utilisateur',
                'label_attr' => [
                    'class' => 'form-label',

                ],
            ])
            ->add('email', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrer une adresse email',
                ],
                'label' => 'Adresse email',
                'label_attr' => [
                    'class' => 'form-label',
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'required' => $options['is_edit'] ? false : true,
                'attr' => [
                    'class' => 'form-control',
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Entrez un mot de passe',
                ],
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'form-label',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdminUser::class,
            'is_edit' => false,
        ]);
    }
}
