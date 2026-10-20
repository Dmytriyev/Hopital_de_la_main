<?php

namespace App\Form;

use App\Entity\SalleOperatoire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class SalleOperatoireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numeroSalle', NumberType::class, [
                'label' => 'Numéro de la salle opératoire',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Entrez le numéro de la salle opératoire',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez le numéro de la salle opératoire',
                    ),
                    new Regex(
                        pattern: '/^\d+$/',
                        message: 'Le numéro de la salle opératoire doit être un nombre entier',
                    ),
                ],
            ])
            ->add('heurOperation', TimeType::class, [
                'label' => 'Heure de l\'opération',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'widget' => 'single_text',
                'help' => 'Entrez l\'heure de l\'opération au format HH:MM',
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez l\'heure de l\'opération',
                    ),
                ],
            ])
            ->add('dureeOperation', TimeType::class, [
                'label' => 'Durée de l\'opération',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'widget' => 'single_text',
                'help' => 'Entrez la durée de l\'opération au format HH:MM',
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez la durée de l\'opération',
                    ),
                ],
            ])
            ->add('details', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Entrez les détails de votre spécialité',
                    'class' => 'form-control',
                ],
                'label' => 'Détails',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez les détails de votre spécialité',
                    ),
                    new Regex(
                        pattern: '/^[a-zA-Z0-9\s.,!?-]+$/',
                        message: 'Les détails de votre spécialité ne peuvent contenir que des lettres, des chiffres et les caractères de ponctuation suivants : . , ! ? -'
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SalleOperatoire::class,
        ]);
    }
}
