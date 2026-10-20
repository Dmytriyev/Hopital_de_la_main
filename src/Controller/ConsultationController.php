<?php

namespace App\Controller;
use App\Entity\Consultation;
use App\Form\ConsultationType;
use App\Repository\ConsultationRepository;
use App\Repository\DossierPatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_MEDECIN')]
#[Route('/consultation')]
final class ConsultationController extends AbstractController
{
    #[Route(name: 'app_consultation_index', methods: ['GET'])]
    public function index(ConsultationRepository $consultationRepository): Response
    {
        return $this->render('consultation/index.html.twig', [
            'consultations' => $consultationRepository->findAll(),
        ]);
    }
    #[Route('/new', name: 'app_consultation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, DossierPatientRepository $dossierPatientRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $consultation = new Consultation();
        $consultation->setMatricule($user->getMatricule());
        $consultation->setMesConsultation($user);

        if ($patientId = $request->query->get('patientId')) {
            $patient = $dossierPatientRepository->find($patientId);
            if ($patient) {
                $consultation->setPatient($patient);
            }
        }

        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consultation->setCreatedAt(new \DateTimeImmutable('now'));
            $consultation->setMatricule($user->getMatricule());
            $consultation->setMesConsultation($user);
            $entityManager->persist($consultation);
            $entityManager->flush();

            if ($patient = $consultation->getPatient()) {
                return $this->redirectToRoute('app_dossier_patient_show', ['id' => $patient->getId()], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('consultation/new.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_consultation_show', methods: ['GET'])]
    public function show(Consultation $consultation): Response
    {
        return $this->render('consultation/show.html.twig', [
            'consultation' => $consultation,
        ]);
    }
    #[Route('/{id}/edit', name: 'app_consultation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('consultation/edit.html.twig', [
            'consultation' => $consultation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_consultation_delete', methods: ['POST'])]
    public function delete(Request $request, Consultation $consultation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consultation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($consultation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_consultation_index', [], Response::HTTP_SEE_OTHER);
    }
}
