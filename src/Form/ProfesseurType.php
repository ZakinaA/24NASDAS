<?php

namespace App\Form;

use App\Entity\Professeur;
use App\Entity\TypeInstrument;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ProfesseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('numRue')
            ->add('rue')
            ->add('copos')
            ->add('ville')
            ->add('tel')
            ->add('mail')
            ->add('type_instrument', EntityType::class, [
                'class' => TypeInstrument::class,
                'choice_label' => 'libelle',
            ])
            ->add('enregistrer', SubmitType::class, [
                'label' => 'Nouveau Prof'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Professeur::class,
        ]);
    }
}
