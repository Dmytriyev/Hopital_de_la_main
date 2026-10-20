<?php

namespace App\Controller;

use App\Entity\RDV;
use App\Form\RDVType;
use App\Repository\DossierPatientRepository;
use App\Repository\RDVRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[Route('/rdv')]
final class RDVController extends AbstractController
{
    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE') or is_granted('ROLE_DIRECTION')"))]
    #[Route(name: 'app_rdv_index', methods: ['GET'])]
    public function index(RDVRepository $rdvRepository): Response
    {
        return $this->render('rdv/index.html.twig', [
            'rdvs' => $rdvRepository->findAll(),
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route('/new', name: 'app_rdv_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, DossierPatientRepository $dossierPatientRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $rdv = new RDV();
        $rdv->setSecretaire($user);

        if ($patientId = $request->query->get('patientId')) {
            $patient = $dossierPatientRepository->find($patientId);
            if ($patient) {
                $rdv->setPatient($patient);
            }
        }

        $form = $this->createForm(RDVType::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rdv->setCreatedAt(new \DateTimeImmutable('now'));
            $rdv->setSecretaire($user);
            $entityManager->persist($rdv);
            $entityManager->flush();

            if ($patient = $rdv->getPatient()) {
                return $this->redirectToRoute('app_dossier_patient_show', ['id' => $patient->getId()], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_rdv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rdv/new.html.twig', [
            'rdv' => $rdv,
            'form' => $form,
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route('/{id}', name: 'app_rdv_show', methods: ['GET'])]
    public function show(RDV $rdv): Response
    {
        return $this->render('rdv/show.html.twig', [
            'rdv' => $rdv,
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route('/{id}/edit', name: 'app_rdv_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RDV $rdv, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RDVType::class, $rdv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rdv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rdv/edit.html.twig', [
            'rdv' => $rdv,
            'form' => $form,
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route('/{id}', name: 'app_rdv_delete', methods: ['POST'])]
    public function delete(Request $request, RDV $rdv, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rdv->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($rdv);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rdv_index', [], Response::HTTP_SEE_OTHER);
    }
}
