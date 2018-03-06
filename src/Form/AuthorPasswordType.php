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

class AuthorPasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add(
        'password',
        PasswordType::class,
        [
            'required' => true,
            'attr' => [
                'placeholder' => 'Set your password.'
            ]
        ])->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'Change password',
                'attr' => array('class' => 'btn btn-primary')
            ]
        )
        ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Author::class
        ]);
    }
}
