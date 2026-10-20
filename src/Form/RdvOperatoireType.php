<?php

namespace App\Form;

use App\Entity\Chambre;
use App\Entity\DossierPatient;
use App\Entity\RdvOperatoire;
use App\Entity\SalleOperatoire;
use App\Entity\User;
use App\Repository\DossierPatientRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RdvOperatoireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('patient', EntityType::class, [
                'class' => DossierPatient::class,
                'choice_label' => function (DossierPatient $dossier): string {
                    return $dossier->getNom() . ' ' . $dossier->getPrenom();
                },
                'query_builder' => function (DossierPatientRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.nom', 'ASC');
                },
                'placeholder' => '-- Sélectionnez un patient --',
                'label' => 'Patient',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
                'help' => 'Sélectionnez le patient concerné par le rendez-vous',
                'constraints' => [
                    new NotBlank(
                        message: 'Veuillez sélectionner un patient',
                    ),
                ],
            ])
            ->add('medecins', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user): string {
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
                'label' => 'Médecin(s)',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-select',
                    'size' => 5,
                ],
                'help' => 'Sélectionnez un ou plusieurs médecins (Ctrl+clic pour sélection multiple)',
                'constraints' => [
                    new NotBlank(
                        message: 'Veuillez sélectionner au moins un médecin',
                    ),
                ],
            ])
            ->add('dateRdvOperatoire', DateType::class, [
                'label' => 'Date du rendez-vous opératoire',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'widget' => 'single_text',
                'help' => 'Entrez la date du rendez-vous opératoire au format DD/MM/YYYY',
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez la date du rendez-vous opératoire',
                    ),
                ],
            ])
            ->add('heurRdvOperatoire', TimeType::class, [
                'label' => 'Heure du rendez-vous opératoire',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'widget' => 'single_text',
                'help' => 'Entrez l\'heure du rendez-vous opératoire au format HH:MM',
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez l\'heure du rendez-vous opératoire',
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
            ->add('chambre', EntityType::class, [
                'class' => Chambre::class,
                'choice_label' => function (Chambre $chambre): string {
                    return 'Chambre ' . $chambre->getNumeroChambre() . ' - ' . $chambre->getTypeChambre();
                },
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.numeroChambre', 'ASC');
                },
                'placeholder' => '-- Sélectionnez une chambre --',
                'label' => 'Chambre',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
                'help' => 'Sélectionnez la chambre pour le rendez-vous opératoire',
            ])
            ->add('salleOperatoire', EntityType::class, [
                'class' => SalleOperatoire::class,
                'choice_label' => function (SalleOperatoire $salle): string {
                    return 'Salle ' . $salle->getNumeroSalle();
                },
                'query_builder' => function ($er) {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.numeroSalle', 'ASC');
                },
                'placeholder' => '-- Sélectionnez une salle opératoire --',
                'label' => 'Salle opératoire',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
                'help' => 'Sélectionnez la salle opératoire pour le rendez-vous opératoire',
             ]) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RdvOperatoire::class,
        ]);
    }
}
