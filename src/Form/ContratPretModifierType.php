<?php

namespace App\Form;
use App\Entity\ContratPret;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class ContratPretModifierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('DateDebut', DateType::class)
            ->add('DateFin', DateType::class)
            ->add('Description', TextType::class,)
            ->add('Prix', MoneyType::class,)
            ->add('Quotite', MoneyType::class,)

            ->add('Eleve', EntityType::class, array('class' => 'App\Entity\Eleve','choice_label' => 'prenom' ))
            ->add('Instrument', EntityType::class, array('class' => 'App\Entity\Instrument','choice_label' => 'numSerie' ))

            ->add('enregistrer', SubmitType::class, [
                'label' => 'Modifier Contrat de pret'
            ])
        ;
    }
 
    public function getParent(){
      return ContratPretType::class;
    }
 
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContratPret::class,
        ]);
    }
}
