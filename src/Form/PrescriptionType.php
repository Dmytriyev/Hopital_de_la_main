<?php

namespace App\Form;

use App\Entity\Prescription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class PrescriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('traitement', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Entrez les détails de votre traitement',
                    'class' => 'form-control',
                ],
                'label' => 'Détails',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez les détails de votre traitement',
                    ),
                    new Regex(
                        pattern: '/^[a-zA-Z0-9\s.,!?-]+$/',
                        message: 'Les détails de votre traitement ne peuvent contenir que des lettres, des chiffres et les caractères de ponctuation suivants : . , ! ? -'
                    ),
                ],
            ])
            ->add('dureeTraitement', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez la durée du traitement',
                    'class' => 'form-control',
                ],
                'label' => 'Durée du traitement',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez la durée du traitement',
                    ),
                    new Regex(
                        pattern: '/^[a-zA-Z0-9\s.,!?-]+$/',
                        message: 'La durée du traitement ne peut contenir que des lettres, des chiffres et les caractères de ponctuation suivants : . , ! ? -'
                    ),
                ],
            ])
            ->add('details', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Entrez les détails de votre traitement',
                    'class' => 'form-control',
                ],
                'label' => 'Détails',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez les détails de votre traitement',
                    ),
                    new Regex(
                        pattern: '/^[a-zA-Z0-9\s.,!?-]+$/',
                        message: 'Les détails de votre traitement ne peuvent contenir que des lettres, des chiffres et les caractères de ponctuation suivants : . , ! ? -'
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prescription::class,
        ]);
    }
}
