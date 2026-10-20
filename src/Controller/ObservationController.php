<?php

namespace App\Controller;

use App\Entity\Observation;
use App\Form\ObservationType;
use App\Repository\ObservationRepository;
use App\Repository\DossierPatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/observation')]
final class ObservationController extends AbstractController
{
    #[IsGranted(new Expression("is_granted('ROLE_INFERMIER') or is_granted('ROLE_MEDECIN')"))]
    #[Route(name: 'app_observation_index', methods: ['GET'])]
    public function index(ObservationRepository $observationRepository): Response
    {
        return $this->render('observation/index.html.twig', [
            'observations' => $observationRepository->findAll(),
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_INFERMIER') or is_granted('ROLE_MEDECIN')"))]
    #[Route('/new', name: 'app_observation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, DossierPatientRepository $dossierPatientRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $observation = new Observation();
        $observation->setMatricule($user->getMatricule());
        $observation->setMesObservation($user);

        if ($patientId = $request->query->get('patientId')) {
            $patient = $dossierPatientRepository->find($patientId);
            if ($patient) {
                $observation->setPatient($patient);
            }
        }

        $form = $this->createForm(ObservationType::class, $observation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $observation->setCreatedAt(new \DateTimeImmutable('now'));
            $observation->setMatricule($user->getMatricule());
            $observation->setMesObservation($user);
            $entityManager->persist($observation);
            $entityManager->flush();

            if ($patient = $observation->getPatient()) {
                return $this->redirectToRoute('app_dossier_patient_show', ['id' => $patient->getId()], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_observation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('observation/new.html.twig', [
            'observation' => $observation,
            'form' => $form,
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_INFERMIER') or is_granted('ROLE_MEDECIN')"))]
    #[Route('/{id}', name: 'app_observation_show', methods: ['GET'])]
    public function show(Observation $observation): Response
    {
        return $this->render('observation/show.html.twig', [
            'observation' => $observation,
        ]);
    }

    #[IsGranted('ROLE_MEDECIN')]
    #[Route('/{id}/edit', name: 'app_observation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Observation $observation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ObservationType::class, $observation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_observation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('observation/edit.html.twig', [
            'observation' => $observation,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_MEDECIN')]
    #[Route('/{id}', name: 'app_observation_delete', methods: ['POST'])]
    public function delete(Request $request, Observation $observation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$observation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($observation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_observation_index', [], Response::HTTP_SEE_OTHER);
    }
}
