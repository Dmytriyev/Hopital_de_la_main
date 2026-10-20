<?php

namespace App\Form;

use App\Entity\Chambre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ChambreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numeroChambre', NumberType::class, [
                'attr' => [
                    'placeholder' => 'Entrez le numéro de la chambre',
                    'class' => 'form-control',
                ],
                'label' => 'Numéro de la chambre',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'help' => 'Entrez le numéro de la chambre',
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez le numéro de la chambre',
                    ),
                    new Regex(
                        pattern: '/^(?:[1-9]|[1-3][0-9]|40)$/',
                        message: 'Le numéro de la chambre doit être composé uniquement de chiffres, nombre de 1 à 40, et ne peut pas commencer par 0'
                    ),
                ],
            ])
            ->add('typeChambre', ChoiceType::class, [
                'choices' => [
                    'Publique' => 'Publique',
                    'Privée' => 'Privee',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Type de chambre',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'help' => 'Sélectionnez le type de chambre',
                'constraints' => [
                    new NotBlank(
                        message: 'Sélectionnez le type de chambre',
                    ),
                ],
            ])
            ->add('alimentation', ChoiceType::class, [
                'choices' => [
                    'Spéciale' => 'Speciale',
                    'Standard' => 'Standard',
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Alimentation',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'help' => 'Sélectionnez le type d\'alimentation',
                'constraints' => [
                    new NotBlank(
                        message: 'Sélectionnez le type d\'alimentation',
                    ),
                ],
            ])
            ->add('dureeOccupation', TimeType::class, [
                'attr' => [
                    'placeholder' => 'Entrez la durée d\'occupation',
                    'class' => 'form-control',
                ],
                'label' => 'Durée d\'occupation',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'help' => 'Entrez la durée d\'occupation',
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez la durée d\'occupation',
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
        ]);
    }
}
