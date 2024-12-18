<?php

namespace App\Form;

use App\Entity\Instrument;
use App\Entity\TypeInstrument;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InstrumentModifierType extends AbstractType
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
            ->add('enregistrer', SubmitType::class, array('label' => 'Modifier'));
        ;
    }

    public function getParent(){
        return InstrumentType::class;
      }
   
      public function configureOptions(OptionsResolver $resolver)
      {
          $resolver->setDefaults([
              'data_class' => Instrument::class,
          ]);
      }
}
