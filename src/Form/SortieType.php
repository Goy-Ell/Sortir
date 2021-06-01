<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,
            [
                'label' => 'Nom de la sortie'
            ])
            ->add('dateHeureDebut', DateTimeType::class,
            [
                'label' => 'Date et heure de la sortie',
                'html5' => true,
                'widget' => 'single_text'


            ])
            ->add('dateLimiteInscription', DateType::class,
            [
                'label' => 'Date limite d\'inscription',
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('nbInscriptionMax', NumberType::class,
            [
                'label' => 'Nb de places'
            ])
            ->add('duree', IntegerType::class,
            [
                'label' => 'Durée',

            ])
            ->add('infosSortie', TextType::class,
            [
                'label' => 'Description et infos'
            ])
            ->add('site', ChoiceType::class,
            [
                'label' => 'Ville'
            ])
            ->add('lieu', ChoiceType::class,
            [
                'label' => 'Lieu'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
