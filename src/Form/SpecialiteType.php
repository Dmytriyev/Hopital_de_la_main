<?php

namespace App\Form;

use App\Entity\Specialite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class SpecialiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('specialisation', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez votre spécialisation',
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'label' => 'Spécialisation',
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez votre spécialisation',
                    ),
                ],
            ])
            ->add('dateDelivrance', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de délivrance',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'JJ/MM/AAAA',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez votre date de délivrance',
                    ),
                    new \Symfony\Component\Validator\Constraints\Callback(function ($date_delivrance, $context) {
                        if (!$date_delivrance instanceof \DateTimeImmutable) {
                            return;
                        }
                        $today = new \DateTimeImmutable();
                        if ($date_delivrance > $today) {
                            $context->buildViolation('Votre date de délivrance ne peut pas être dans le futur')
                                ->addViolation();
                        }
                    }),
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
            'data_class' => Specialite::class,
        ]);
    }
}
