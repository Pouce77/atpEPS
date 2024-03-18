<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'label' => 'Votre Email',
                'attr' => ['class' => 'form-control mb-2 mt-1', 'placeholder' =>'Votre Email'],
                'required' => true,
            ])
            ->add('message', TextareaType::class,[
                'label' => 'Votre message',
                'attr' => ['class' => 'form-control mt-1 ', 'rows' => "5",'placeholder' =>'Votre Message'],
                'required' => true,
            ])
            ->add('send', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => ['class' => 'btn btn-primary mt-3'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
