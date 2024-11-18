<?php

namespace App\Form;

use App\Entity\Eleve;
use App\Form\EleveType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EleveModifierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('numRue', IntegerType::class)
            ->add('rue', TextType::class)
            ->add('copos', IntegerType::class)
            ->add('ville', TextType::class)
            ->add('tel', IntegerType::class)
            ->add('mail', TextType::class)
            ->add('enregistrer', SubmitType::class, array('label' => 'Modifier'));
    }

    public function getParent(){
        return EleveType::class;
      }
   
      public function configureOptions(OptionsResolver $resolver)
      {
          $resolver->setDefaults([
              'data_class' => Eleve::class,
          ]);
      }
}
