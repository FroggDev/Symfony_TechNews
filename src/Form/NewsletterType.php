<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 06/03/2018
 * Time: 12:08
 */

namespace App\Form;

use App\Entity\Newsletter;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpKernel\Tests\Controller;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class NewsletterType
 * @package App\Form
 */
class NewsletterType extends AbstractType
{

    public function __construct()
    {
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            //->setAction($this->router->generateUrl('target_route'))
            ->add(
                'email',
                EmailType::class,
                [
                    'required' => true,
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Your email...',
                        'class' => 'form-control'
                    ]
                ]
            )->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Subscribe',
                    'attr' => array('class' => 'btn btn-primary my-btn')
                ]
            )->getForm();
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Newsletter::class
        ]);
    }
}
