<?php

namespace App\Controller;

use App\Entity\RdvOperatoire;
use App\Form\RdvOperatoireType;
use App\Repository\DossierPatientRepository;
use App\Repository\RdvOperatoireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/rdvOperatoire')]
final class RdvOperatoireController extends AbstractController
{
    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE') or is_granted('ROLE_INFERMIER')"))]
    #[Route(name: 'app_rdv_operatoire_index', methods: ['GET'])]
    public function index(RdvOperatoireRepository $rdvOperatoireRepository): Response
    {
        return $this->render('rdv_operatoire/index.html.twig', [
            'rdv_operatoires' => $rdvOperatoireRepository->findAll(),
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route('/new', name: 'app_rdv_operatoire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, DossierPatientRepository $dossierPatientRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $rdvOperatoire = new RdvOperatoire();
        $rdvOperatoire->setSecretaire($user);

        if ($patientId = $request->query->get('patientId')) {
            $patient = $dossierPatientRepository->find($patientId);
            if ($patient) {
                $rdvOperatoire->setPatient($patient);
            }
        }

        $form = $this->createForm(RdvOperatoireType::class, $rdvOperatoire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rdvOperatoire->setSecretaire($user);
            $entityManager->persist($rdvOperatoire);
            $entityManager->flush();

            if ($patient = $rdvOperatoire->getPatient()) {
                return $this->redirectToRoute('app_dossier_patient_show', ['id' => $patient->getId()], Response::HTTP_SEE_OTHER);
            }
            return $this->redirectToRoute('app_rdv_operatoire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rdv_operatoire/new.html.twig', [
            'rdv_operatoire' => $rdvOperatoire,
            'form' => $form,
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')or is_granted('ROLE_INFERMIER')"))]
    #[Route('/{id}', name: 'app_rdv_operatoire_show', methods: ['GET'])]
    public function show(RdvOperatoire $rdvOperatoire): Response
    {
        return $this->render('rdv_operatoire/show.html.twig', [
            'rdv_operatoire' => $rdvOperatoire,
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route('/{id}/edit', name: 'app_rdv_operatoire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RdvOperatoire $rdvOperatoire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RdvOperatoireType::class, $rdvOperatoire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_rdv_operatoire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rdv_operatoire/edit.html.twig', [
            'rdv_operatoire' => $rdvOperatoire,
            'form' => $form,
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route('/{id}', name: 'app_rdv_operatoire_delete', methods: ['POST'])]
    public function delete(Request $request, RdvOperatoire $rdvOperatoire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rdvOperatoire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($rdvOperatoire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_rdv_operatoire_index', [], Response::HTTP_SEE_OTHER);
    }
}
