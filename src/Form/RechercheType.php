<?php

namespace App\Form;

use App\Entity\Recherche;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('site',EntityType::class,[
                'required'=>false
            ])
            ->add('nom',TextType::class,[
                'label' => "Le nom de la sortie contient",
                'required'=>false
            ])
            ->add('dateMin',DateType::class,[
                'label' => "entre",
                'required'=>false,
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('dateMax',DateType::class,[
                'label' => "et",
                'required'=>false,
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('organisateur',CheckboxType::class,[
                'label' => "Sorties dont je suis l'organisateur/trice",
                'required'=>false
            ])
            ->add('inscrit',CheckboxType::class,[
                'label' => "Sorties auquelles je suis inscrit/e",
                'required'=>false
            ])
            ->add('pasInscrit',CheckboxType::class,[
                'label' => "Sorties auquelles je ne suis pas inscrit/e",
                'required'=>false
            ])
            ->add('passees',CheckboxType::class,[
                'label' => "Sorties passées",
                'required'=>false
            ])
            ->add('Rechercher',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recherche::class,
        ]);
    }
}
