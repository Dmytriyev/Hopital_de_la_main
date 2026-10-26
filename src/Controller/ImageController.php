<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\DossierPatientRepository;
use App\Repository\ImageRepository;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/image')]
final class ImageController extends AbstractController
{
    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route(name: 'app_image_index', methods: ['GET'])]
    public function index(ImageRepository $imageRepository): Response
    {
        return $this->render('image/index.html.twig', [
            'images' => $imageRepository->findAll(),
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route('/new', name: 'app_image_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, DossierPatientRepository $dossierPatientRepository): Response
    {
        $image = new Image();

        if ($patientId = $request->query->get('patientId')) {
            $patient = $dossierPatientRepository->find($patientId);
            if ($patient) {
                $image->setImagePatient($patient);
            }
        }

        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('imageFile')->getData();
            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                try {
                    $uploadedFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/images',
                        $newFilename
                    );
                    $image->setImageFile($newFilename);
                } catch (FileException $e) {
                    // Correction : on n'enregistre plus un nom de fichier qui n'existe pas sur le disque
                    $this->addFlash('error', 'Erreur lors du téléchargement du fichier.');
                }
            }
            $image->setCreatedAt(new \DateTimeImmutable('now'));
            $entityManager->persist($image);
            $entityManager->flush();

            if ($patient = $image->getImagePatient()) {
                return $this->redirectToRoute('app_dossier_patient_show', ['id' => $patient->getId()], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('image/new.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }
    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route('/{id}', name: 'app_image_show', methods: ['GET'])]
    public function show(Image $image): Response
    {
        return $this->render('image/show.html.twig', [
            'image' => $image,
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN')"))]
    #[Route('/{id}/edit', name: 'app_image_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Image $image, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('imageFile')->getData();
            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                try {
                    $uploadedFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/images',
                        $newFilename
                    );
                    $image->setImageFile($newFilename);
                } catch (FileException $e) {
                    // Correction : on n'enregistre plus un nom de fichier qui n'existe pas sur le disque
                    $this->addFlash('error', 'Erreur lors du téléchargement du fichier.');
                }
            }
            $image->setUpdatedAt(new \DateTimeImmutable('now'));
            $entityManager->flush();

            return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('image/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }
    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN')"))]
    #[Route('/{id}', name: 'app_image_delete', methods: ['POST'])]
    public function delete(Request $request, Image $image, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($image);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_image_index', [], Response::HTTP_SEE_OTHER);
    }
}
