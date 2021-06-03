<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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
           //     'widget' => 'single_text'


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

            ->add('lieu', EntityType::class,
            [
                'class' => Lieu::class,
                'label' => 'Lieu',
                'choice_label' => 'nom',
                'placeholder' => 'Lieu',

            ])

            ->add('latitude', TextType::class,
            [
                'mapped' => false,
                'label' => 'Latitude'

            ])
            ->add('longitude', TextType::class,
            [
                'mapped' => false,
                'label' => 'Longitude'
            ])

        ;

            /*$formModifier = function (FormInterface $form, Ville $site = null){
                $lieu = null === $site ? [] : $site->getLieux();

                $form->add('lieu', EntityType::class, [
                    'class' => Lieu::class,
                    'choices' => $lieu,
                    'choice_label' => 'nom',
                    'placeholder' => 'Lieu (Choisir une ville)',
                    'label' => 'Lieu'
                ]);
            };

            $builder->get('site')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier){
                    $site = $event->getForm()->getData();
                    $formModifier($event->getForm()->getParent(), $site);
                }
            );*/

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
