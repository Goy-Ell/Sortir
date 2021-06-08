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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,
            [
                'label' => 'Nom de la sortie'
            ])
            ->add('photoSortie', FileType::class, [
                'mapped'=>false,
                'required'=>false,
                'constraints'=>[
                    new Image([
                        'maxSize'=>'7024k',
                        'mimeTypesMessage'=>'Format image incompatible ! '
                    ])
                ]
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
                'required' => false,
                'mapped' => false,
                'label' => 'Latitude'

            ])
            ->add('longitude', TextType::class,
            [
                'required' => false,
                'mapped' => false,
                'label' => 'Longitude'
            ])

        ;

            /*$formModifier = function (FormInterface $form, Lieu $lieu = null){
                $rue = (null === $lieu) ? [] : $lieu->getRue();


                ]);
            };

            $builder->get('lieu')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier){
                    $lieu = $event->getForm()->getData();
                    $formModifier($event->getForm()->getParent(), $lieu);
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
