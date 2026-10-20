<?php

namespace App\Form;

use App\Entity\DossierPatient;
use App\Entity\RDV;
use App\Entity\User;
use App\Repository\DossierPatientRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RDVType extends AbstractType
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
            ->add('medecin', EntityType::class, [
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
                'placeholder' => '-- Sélectionnez un médecin --',
                'label' => 'Médecin',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-select',
                ],
                'help' => 'Sélectionnez le médecin responsable du rendez-vous',
                'constraints' => [
                    new NotBlank(
                        message: 'Veuillez sélectionner un médecin',
                    ),
                ],
            ])
            ->add('dateRdv', DateType::class, [
                'label' => 'Date du rendez-vous',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'widget' => 'single_text',
                'help' => 'Entrez la date du rendez-vous au format DD/MM/YYYY',
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez la date du rendez-vous',
                    ),
                ],
            ])
            ->add('heurRdv', TimeType::class, [
                'label' => 'Heure du rendez-vous',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'widget' => 'single_text',
                'help' => 'Entrez l\'heure du rendez-vous au format HH:MM',
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez l\'heure du rendez-vous',
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RDV::class,
        ]);
    }
}
