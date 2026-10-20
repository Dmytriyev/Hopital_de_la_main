<?php

namespace App\Controller;

use App\Entity\DossierPatient;
use App\Form\DossierPatientMedecinType;
use App\Form\DossierPatientType;
use App\Repository\DossierPatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dossierPatient')]
final class DossierPatientController extends AbstractController
{
    #[IsGranted(new Expression("is_granted('ROLE_INFERMIER') or is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route(name: 'app_dossier_patient_index', methods: ['GET'])]
    public function index(DossierPatientRepository $dossierPatientRepository): Response
    {
        return $this->render('dossier_patient/index.html.twig', [
            'dossier_patients' => $dossierPatientRepository->findAll(),
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_SECRETAIRE') or is_granted('ROLE_MEDECIN')"))]
    #[Route('/new', name: 'app_dossier_patient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dossierPatient = new DossierPatient();
        $form = $this->createForm(DossierPatientType::class, $dossierPatient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dossierPatient->setCreatedAt(new \DateTimeImmutable('now'));
            $dossierPatient->setNumeroDossier(0);
            $entityManager->flush();

            // Exemple: year (ex: 2026) + l'id (ex: 42) = 202642
            $numeroDossier = (int)((new \DateTimeImmutable('now'))->format('Y') . $dossierPatient->getId());
            $dossierPatient->setNumeroDossier($numeroDossier);
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_patient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dossier_patient/new.html.twig', [
            'dossier_patient' => $dossierPatient,
            'form' => $form,
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_INFERMIER') or is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route('/{id}', name: 'app_dossier_patient_show', methods: ['GET'])]
    public function show(DossierPatient $dossierPatient): Response
    {
        return $this->render('dossier_patient/show.html.twig', [
            'dossier_patient' => $dossierPatient,
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route('/{id}/edit', name: 'app_dossier_patient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DossierPatient $dossierPatient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DossierPatientType::class, $dossierPatient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dossierPatient->setUpdatedAt(new \DateTimeImmutable('now'));
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_patient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dossier_patient/edit.html.twig', [
            'dossier_patient' => $dossierPatient,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_MEDECIN')]
    #[Route('/{patientId}/modifier-medecin', name: 'app_dossier_patient_modifier_medecin', methods: ['GET', 'POST'])]
    public function modifierMedecin(Request $request, DossierPatientRepository $dossierPatientRepository, EntityManagerInterface $entityManager, int $patientId): Response
    {
        $dossierPatient = $dossierPatientRepository->find($patientId);
        if (!$dossierPatient) {
            throw $this->createNotFoundException('Dossier patient introuvable.');
        }

        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        if (!$dossierPatient->getMesMedecins()->contains($user)) {
            throw $this->createAccessDeniedException('Seul un médecin déjà associé à ce dossier peut modifier les médecins associés.');
        }

        $form = $this->createForm(DossierPatientMedecinType::class, $dossierPatient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dossierPatient->setUpdatedAt(new \DateTimeImmutable('now'));
            $entityManager->flush();

            return $this->redirectToRoute('app_dossier_patient_show', ['id' => $dossierPatient->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dossier_patient/modifier_medecin.html.twig', [
            'dossier_patient' => $dossierPatient,
            'form' => $form,
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route('/{id}', name: 'app_dossier_patient_delete', methods: ['POST'])]
    public function delete(Request $request, DossierPatient $dossierPatient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $dossierPatient->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($dossierPatient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dossier_patient_index', [], Response::HTTP_SEE_OTHER);
    }
}
