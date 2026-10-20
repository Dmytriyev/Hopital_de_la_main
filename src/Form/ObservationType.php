<?php

namespace App\Form;

use App\Entity\DossierPatient;
use App\Entity\Observation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ObservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => true, // valeur injectée automatiquement depuis l'utilisateur connecté
                ],
                'label' => 'Matricule',
                'label_attr' => [
                    'class' => 'form-label',
                ],
            ])
            ->add('patient', EntityType::class, [
                'class' => DossierPatient::class,
                'choice_label' => function (DossierPatient $dossier) {
                    return $dossier->getNom() . ' ' . $dossier->getPrenom();
                },
                'label' => 'Patient',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Sélectionnez un patient',
                'required' => true,
            ])
            ->add('observation', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Entrez les détails de votre observation',
                    'class' => 'form-control',
                ],
                'label' => 'Détails',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez les détails de votre observation',
                    ),
                    new Regex(
                        pattern: '/^[a-zA-Z0-9\s.,!?-]+$/',
                        message: 'Les détails de votre observation ne peuvent contenir que des lettres, des chiffres et les caractères de ponctuation suivants : . , ! ? -'
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Observation::class,
        ]);
    }
}
