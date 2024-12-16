<?php

namespace App\Form;

use App\Entity\Instrument;
use App\Entity\Modele;
use App\Entity\Marque;
use App\Entity\Couleur;
use App\Entity\TypeInstrument;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InstrumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numSerie')
            ->add('dateAchat', null, [
                'widget' => 'single_text',
            ])
            ->add('prixAchat')
            ->add('utilisation')
            ->add('cheminImage')
            ->add('type_instrument', EntityType::class, [
                'class' => TypeInstrument::class,
                'choice_label' => 'libelle',
            ])
            ->add('modele', EntityType::class, [
                'class' => Modele::class,
                'choice_label' => 'nom',
            ])
            ->add('marque', EntityType::class, [
                'class' => Marque::class,
                'choice_label' => 'libelle',
            ])
            ->add('couleur', EntityType::class, [
                'class' => Couleur::class,
                'choice_label' => 'libelle',
            ])
            ->add('enregistrer', SubmitType::class, [
                'label' => 'Nouvel Instrument'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Instrument::class,
        ]);
    }
}
