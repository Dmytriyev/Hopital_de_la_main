<?php

namespace App\Form;

use App\Entity\DossierPatient;
use App\Entity\Image;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image_patient', EntityType::class, [
                'class' => DossierPatient::class,
                'choice_label' => fn(DossierPatient $d) => $d->getNom() . ' ' . $d->getPrenom(),
                'label' => 'Dossier patient',
                'label_attr' => ['class' => 'form-label'],
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Sélectionnez un patient',
                'required' => false,
            ])
            ->add('fileName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Entrez le nom de l\'image',
                    'class' => 'form-control',
                ],
                'label' => 'Nom de l\'image',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                                'help' => 'Entrez le nom de l\'image',
                'constraints' => [
                    new NotBlank(
                        message: 'Entrez le nom de l\'image',
                    ),
                    new Regex(
                        pattern: '/^[a-zA-Z0-9\s.,!?-]+$/',
                        message: 'Le nom de l\'image doit contenir uniquement des lettres, des chiffres, des espaces et les caractères de ponctuation suivants : . , ! ? -',
                    ),
                ],

            ])
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Fichier image',
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'help' => 'Sélectionnez une image à télécharger',
                'constraints' => [
                    new NotBlank(
                        message: 'Sélectionnez une image à télécharger',
                    ),
                    // Sécurité : le SVG est refusé, car il peut contenir du JavaScript
                    // exécuté par le navigateur (faille XSS stockée dans /public/uploads).
                    new File(
                        maxSize: '10M',
                        mimeTypes: ['image/jpeg', 'image/png', 'application/pdf'],
                        mimeTypesMessage: 'Le fichier doit être au format JPG, JPEG, PNG ou PDF',
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
