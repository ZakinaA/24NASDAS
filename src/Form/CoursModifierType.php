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
