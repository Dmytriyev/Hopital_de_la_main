<?php

namespace App\Form;

use App\Entity\DossierPatient;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class DossierPatientType extends AbstractType
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
                        max: 100,
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
            ->add('dateNaissance',DateType::class, [
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
            ->add('numeroSecu', TextType::class, [
                'label' => 'Numéro de sécurité sociale',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => ['placeholder' => 'Sélectionnez votre numéro de sécurité sociale', 'class' => 'form-control'],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez votre numéro de sécurité sociale',
                    ),
                    new Regex(
                        pattern: '/^[1-2]\d{14}$/',
                        message: 'Veuillez entrer un numéro de sécurité sociale valide (15 chiffres)',
                    )
                ],
            ])
                ->add('nomMedecinTraitant', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getNom() . ' ' . $user->getPrenom();
                },
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%"ROLE_MEDECIN"%')
                        ->orderBy('u.nom', 'ASC');
                },
                'label' => 'Médecin traitant principal',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'placeholder' => 'Sélectionnez le médecin traitant',
                'help' => 'Un seul médecin traitant principal pour ce dossier',
                'required' => false,
            ])

            ->add('nomConfiance', TextType::class, [
                'label' => 'Nom de la personne de confiance',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Sélectionnez le nom de la personne de confiance',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez le nom de la personne de confiance',
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
            ->add('prenomConfiance', TextType::class, [
                'label' => 'Prénom de la personne de confiance',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Sélectionnez le prénom de la personne de confiance',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez le prénom de la personne de confiance',
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
            ->add('telephoneConfiance', TextType::class, [
                'label' => 'Téléphone de la personne de confiance',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'placeholder' => 'Sélectionnez le numéro de téléphone de la personne de confiance',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez le numéro de téléphone de la personne de confiance',
                    ),
                    new Regex(
                        pattern: '/^[0-9+]{10,13}$/',
                        message: 'Veuillez entrer un numéro de téléphone valide (10 à 13 chiffres, peut commencer par +)',
                    ),
                ],
            ])
            ->add('details', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Entrez les détails ou remarques',
                    'class' => 'form-control',
                ],
                'label' => 'Détails',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'constraints' => [
                    new Regex(
                        pattern: '/^[a-zA-Z0-9\s.,!?-]+$/',
                        message: 'Les détails ou remarques ne peuvent contenir que des lettres, des chiffres et les caractères de ponctuation suivants : . , ! ? -'
                    ),
                ],
            ])
            ->add('mesMedecins', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return $user->getNom() . ' ' . $user->getPrenom();
                },
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%"ROLE_MEDECIN"%')
                        ->orderBy('u.nom', 'ASC');
                },
                'multiple' => true,
                'expanded' => false,
                'by_reference' => false,
                'label' => 'Médecins associés au dossier',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'size' => 5,
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DossierPatient::class,
        ]);
    }
}
