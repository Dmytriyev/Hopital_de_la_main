<?php

namespace App\Controller;

use App\Entity\Prescription;
use App\Form\PrescriptionType;
use App\Repository\ConsultationRepository;
use App\Repository\PrescriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/prescription')]
final class PrescriptionController extends AbstractController
{
    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route(name: 'app_prescription_index', methods: ['GET'])]
    public function index(PrescriptionRepository $prescriptionRepository): Response
    {
        return $this->render('prescription/index.html.twig', [
            'prescriptions' => $prescriptionRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_MEDECIN')]
    #[Route('/new', name: 'app_prescription_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ConsultationRepository $consultationRepository): Response
    {
        $prescription = new Prescription();

        $consultationId = $request->query->get('consultationId');
        $consultation = $consultationId ? $consultationRepository->find($consultationId) : null;

        if (!$consultation) {
            throw $this->createNotFoundException('Une prescription doit être créée depuis une consultation existante.');
        }

        $prescription->setMesPrescriptions($consultation);

        $form = $this->createForm(PrescriptionType::class, $prescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prescription->setCreatedAt(new \DateTimeImmutable('now'));
            $entityManager->persist($prescription);
            $entityManager->flush();

            return $this->redirectToRoute('app_consultation_show', ['id' => $consultation->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prescription/new.html.twig', [
            'prescription' => $prescription,
            'consultation' => $consultation,
            'form' => $form,
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route('/{id}', name: 'app_prescription_show', methods: ['GET'])]
    public function show(Prescription $prescription): Response
    {
        return $this->render('prescription/show.html.twig', [
            'prescription' => $prescription,
        ]);
    }

    #[IsGranted('ROLE_MEDECIN')]
    #[Route('/{id}/edit', name: 'app_prescription_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Prescription $prescription, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PrescriptionType::class, $prescription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_prescription_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('prescription/edit.html.twig', [
            'prescription' => $prescription,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_MEDECIN')]
    #[Route('/{id}', name: 'app_prescription_delete', methods: ['POST'])]
    public function delete(Request $request, Prescription $prescription, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prescription->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($prescription);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_prescription_index', [], Response::HTTP_SEE_OTHER);
    }
}
