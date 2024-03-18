<?php

namespace App\Form;

use App\Entity\Points;
use App\Entity\User;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('abovePoints',CollectionType::class,[
                'label' => 'Points gagnés pour un adversaire classé au-dessus avec un écart de :',
                'entry_options' => [
                    'attr' => ['class' => 'm-1 rounded','type'=>'number'],
                ],
            ])
            ->add('underPoints',CollectionType::class,[
                'label' => 'Points gagnés pour un adversaire classé au-dessous avec un écart de :',
                'entry_options' => [
                    'attr' => ['class' => 'm-1 rounded', 'type'=>'number'],
                ],
            ])
            ->add('matchLostPoints',NumberType::class,[
                'label' => 'Point(s) pour un match perdu',
                'attr' => ['class' => 'm-1 rounded', 'type'=>'number']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Points::class,
        ]);
    }
}
