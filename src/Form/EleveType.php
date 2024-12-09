<?php

namespace App\Form;

use App\Entity\Eleve;
use App\Entity\Responsable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class EleveType extends AbstractType
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
            ->add('responsable', EntityType::class, [
                'class' => Responsable::class,
                'choice_label' => 'nom',
            ])
            ->add('enregistrer', SubmitType::class, array('label' => 'Ajouter un élève'))

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Eleve::class,
        ]);
    }
}
