<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Repository\LieuRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

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
                'attr' => ['id' => 'dateHeureDebut'],
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
            ->add('lieu', EntityType::class, [
                'label' => 'Lieu : ',
                'class' => Lieu::class,
                'query_builder' => function (LieuRepository $lieuRepository) {
                    return $lieuRepository->createQueryBuilder('lieu')
                        ->orderBy('lieu.nom', 'ASC');
                },
                'choice_label' => 'nom'
            ])
            ->add('rue', EntityType::class, [
                'label' => 'Rue : ',
                'class' => Lieu::class,
                'choice_label' => 'rue',
                'query_builder' => function (LieuRepository $lieuRepository) {
                    return $lieuRepository->createQueryBuilder('lieu')
                        ->orderBy('lieu.rue', 'ASC');
                },
                'mapped' => false,

            ])
            ->add('codePostal', NumberType::class, [
                'label' => 'Code postal : ',
                'mapped' => false,
                'disabled' => true
            ])
            ->add('latitude', NumberType::class, [
                'label' => 'Latitude : ',
                'mapped' => false,
                'disabled' => true
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude : ',
                'mapped' => false,
                'disabled' => true
            ])
            ->add('enregistrer', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['style' => 'display: inline-flex; justify-content: center']
            ])
            ->add('publier', SubmitType::class, [
                'label' => 'Publier',
            ])
            ->add('annuler', SubmitType::class, [
                'label' => 'Annuler',
                'attr' => ['style' => 'display: inline-flex; justify-content: center']

            ])
            ;
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
