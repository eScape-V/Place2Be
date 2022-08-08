<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la sortie',

            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie',
                'widget' => 'single_text',
                'html5' => true,
                'required' => true,
            ])
            ->add('dateLimiteInscription', DateType::class, [
                'label' => 'Date limite d\'inscription',
                'widget' => 'single_text',
                'html5' => true,
                'required' => true,
                'attr' => array('class' => 'smallBtn')

            ])
            ->add('nbInscriptionsMax', NumberType::class, [
                'label' => 'Nombre de places',
                'attr' => array('style' => 'width: 80px; align-self: center')
            ])
            ->add('duree', NumberType::class, [
                'label' => 'Durée (minutes)',
                'attr' => array('style' => 'width: 80px')

            ])
            ->add('infosSortie', TextareaType::class, [
                'label' => 'Description et infos'
            ])
            ->add('campus', EntityType::class, [
                'class'=> Campus::class,
                'choice_label' => 'nom'
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class
            ])
            ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer'])
            ->add('publier', SubmitType::class, ['label' => 'Publier'])
            ->add('annuler', SubmitType::class, ['label' => 'Annuler'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
