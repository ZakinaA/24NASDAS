<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Jour;
use App\Entity\Professeur;
use App\Entity\TypeCours;
use App\Entity\TypeInstrument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CoursModifierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
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
                'label' => 'Modifier Cours'
            ])
        ;
    }
 
    public function getParent(){
      return CoursType::class;
    }
 
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
