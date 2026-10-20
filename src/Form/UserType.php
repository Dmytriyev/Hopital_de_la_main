<?php

namespace App\Form;

use App\Entity\Specialite;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sexe', ChoiceType::class, [
                'label' => 'Sexe',
                'label_attr' => [
                    'class' => 'form-choice-label',
                ],
                'attr' => [
                    'class' => 'form-choice',
                ],
                'choices' => [
                    'Homme' => 'Homme',
                    'Femme' => 'Femme',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Sélectionnez votre sexe',
                    ),
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Sélectionnez votre nom',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez votre nom',
                    ),
                    new Length(
                        min: 2,
                        minMessage: 'Votre nom doit comporter au moins {{ limit }} caractères',
                        max: 50,
                        maxMessage: 'Votre nom ne peut pas dépasser {{ limit }} caractères',
                    ),
                    new Regex(
                        pattern: '/^[a-zA-ZÀ-ÿ\' -]+$/',
                        message: 'Votre nom ne peut contenir que des lettres, des espaces, des tirets et des apostrophes',
                    ),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Sélectionnez votre prénom',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez votre prénom',
                    ),
                    new Length(
                        min: 2,
                        minMessage: 'Votre prénom doit comporter au moins {{ limit }} caractères',
                        max: 50,
                        maxMessage: 'Votre prénom ne peut pas dépasser {{ limit }} caractères',
                    ),
                    new Regex(
                        pattern: '/^[a-zA-ZÀ-ÿ\' -]+$/',
                        message: 'Votre prénom ne peut contenir que des lettres, des espaces, des tirets et des apostrophes',
                    ),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Entrez votre email',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez votre email',
                    ),
                    new Length(
                        min: 2,
                        minMessage: 'Votre email doit comporter au moins {{ limit }} caractères',
                        max: 180,
                        maxMessage: 'Votre email ne peut pas dépasser {{ limit }} caractères',
                    ),
                    new Regex(
                        pattern: '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                        message: 'Veuillez entrer une adresse email valide',
                    ),
                ],
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Sélectionnez votre numéro de téléphone',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez votre numéro de téléphone',
                    ),
                    new Regex(
                        pattern: '/^[0-9+]{10,13}$/',
                        message: 'Veuillez entrer un numéro de téléphone valide (10 à 13 chiffres, peut commencer par +)',
                    ),
                ],
            ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de naissance',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'JJ/MM/AAAA',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez votre date de naissance',
                    ),
                    new \Symfony\Component\Validator\Constraints\Callback(function ($dateNaissance, $context) {
                        if (!$dateNaissance instanceof \DateTimeImmutable) {
                            return;
                        }
                        $today = new \DateTimeImmutable();
                        if ($dateNaissance > $today) {
                            $context->buildViolation('Votre date de naissance ne peut pas être dans le futur')
                                ->addViolation();
                        }
                    }),
                ],
            ])
            ->add('adresse', TextareaType::class, [
                'label' => 'Adresse',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Sélectionnez votre adresse',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez votre adresse',
                    ),
                    new Length(
                        min: 5,
                        minMessage: 'Votre adresse doit comporter au moins {{ limit }} caractères',
                        max: 255,
                        maxMessage: 'Votre adresse ne peut pas dépasser {{ limit }} caractères',
                    ),
                    new Regex(
                        pattern: '/^[a-zA-Z0-9À-ÿ\'., -]+$/',
                        message: 'Votre adresse ne peut contenir que des lettres, des chiffres, des espaces, des tirets, des apostrophes et des points',
                    ),
                ],
            ])
            ->add('codePostale', NumberType::class, [
                'label' => 'Code postal',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Sélectionnez votre code postal',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez votre code postal',
                    ),
                    new Regex(
                        pattern: '/^\d{5}$/',
                        message: 'Veuillez entrer un code postal valide (5 chiffres)',
                    ),
                ],
            ])
            ->add('post', TextType::class, [
                'label' => 'Poste occupé',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => ['placeholder' => 'Sélectionnez votre poste occupé', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez le poste occupé',
                    ),
                    new Length(
                        min: 2,
                        minMessage: 'Le poste occupé doit comporter au moins {{ limit }} caractères',
                        max: 100,
                        maxMessage: 'Le poste occupé ne peut pas dépasser {{ limit }} caractères',
                    ),
                    new Regex(
                        pattern: '/^[a-zA-ZÀ-ÿ\' -]+$/',
                        message: 'Le poste occupé ne peut contenir que des lettres, des espaces, des tirets et des apostrophes',
                    ),
                ],
            ])
            ;

        // Le champ spécialisations n'est visible que pour les médecins
        if ($options['data'] instanceof User && in_array('ROLE_MEDECIN', $options['data']->getRoles())) {
            $builder->add('mes_specialites', EntityType::class, [
                'class' => Specialite::class,
                'choice_label' => 'specialisation',
                'label' => 'Spécialisations',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
                'multiple' => true,
                'expanded' => false,
                'required' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
