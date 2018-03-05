<?php

namespace App\Form;

use App\Entity\Author;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add(
            'firstname',
            TextType::class,
            [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Set your firstname.'
                ]
            ]
        )->add(
            'lastname',
            TextType::class,
            [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Set your lastname.'
                ]
            ]
        )->add(
            'email',
            EmailType::class,
            [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Set your email.'
                ]
            ]
        )->add(
            'password',
            PasswordType::class,
            [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Set your password.'
                ]
            ]
        )->add(
            'passwordCheck',
            PasswordType::class,
            [
                'required' => true,
                'attr' => [
                    'placeholder' => 're-type your password.'
                ]
            ]
        )->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'Create account',
                'attr' => array('class' => 'btn btn-primary')
            ]
        )->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Author::class
        ]);
    }
}
