<?php

namespace App\Form;

use App\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class)
            ->add('ageMini', IntegerType::class)
            ->add('heureDebut', TimeType::class, [
                'widget' => 'single_text',  
                'input' => 'datetime',     
                'with_seconds' => false,    
            ])
            ->add('heureFin', TimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'with_seconds' => false,  
            ])

            ->add('professeur', EntityType::class, array('class' => 'App\Entity\Professeur','choice_label' => 'nom' ))
            ->add('typeCours', EntityType::class, array('class' => 'App\Entity\TypeCours','choice_label' => 'libelle' ))
            ->add('typeInstrument', EntityType::class, array('class' => 'App\Entity\TypeInstrument','choice_label' => 'libelle' ))
            ->add('jour', EntityType::class, array('class' => 'App\Entity\Jour','choice_label' => 'libelle' ))
            ->add('cheminImage')

            ->add('enregistrer', SubmitType::class, [
                'label' => 'Nouveaux Cours'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
