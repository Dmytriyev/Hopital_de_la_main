<?php

namespace App\Form;

use App\Entity\Consultation;
use App\Entity\DossierPatient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('patient', EntityType::class, [
                'class' => DossierPatient::class,
                'choice_label' => fn(DossierPatient $d) => $d->getNom() . ' ' . $d->getPrenom(),
                'label' => 'Dossier patient',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Sélectionnez un patient',
                'required' => false,
            ])
            ->add('matricule', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'readonly' => true,  // valeur injectée automatiquement depuis l'utilisateur connecté
                ],
                'label' => 'Matricule',
                'label_attr' => [
                    'class' => 'form-label',
                ],
            ])
            ->add('compteRendu', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Entrez les détails de votre compte rendu',
                    'class' => 'form-control',
                ],
                'label' => 'Détails',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez les détails de votre compte rendu',
                    ),
                    new Regex(
                        pattern: '/^[a-zA-Z0-9\s.,!?-]+$/',
                        message: 'Les détails de votre compte rendu ne peuvent contenir que des lettres, des chiffres et les caractères de ponctuation suivants : . , ! ? -'
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
