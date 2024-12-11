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



class ContratPretType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('DateDebut', DateType::class)
            ->add('DateFin', DateType::class)
            ->add('Description', TextType::class,)
            ->add('Prix', MoneyType::class,)
            ->add('Quotite', MoneyType::class,)

            ->add('Eleve', EntityType::class, array('class' => 'App\Entity\Eleve','choice_label' => 'prenom' ))
            ->add('Instrument', EntityType::class, array('class' => 'App\Entity\TypeInstrument','choice_label' => 'libelle' ))

            ->add('enregistrer', SubmitType::class, [
                'label' => 'Nouveaux Contrat de pret'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContratPret::class,
        ]);
    }
}
