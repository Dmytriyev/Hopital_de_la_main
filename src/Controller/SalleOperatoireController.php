<?php

namespace App\Controller;

use App\Entity\SalleOperatoire;
use App\Form\SalleOperatoireType;
use App\Repository\SalleOperatoireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/salleOperatoire')]
final class SalleOperatoireController extends AbstractController
{
    #[IsGranted(new Expression("is_granted('ROLE_INFERMIER') or is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE') or is_granted('ROLE_ADMIN')"))]
    #[Route(name: 'app_salle_operatoire_index', methods: ['GET'])]
    public function index(SalleOperatoireRepository $salleOperatoireRepository): Response
    {
        return $this->render('salle_operatoire/index.html.twig', [
            'salle_operatoires' => $salleOperatoireRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'app_salle_operatoire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $salleOperatoire = new SalleOperatoire();
        $form = $this->createForm(SalleOperatoireType::class, $salleOperatoire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($salleOperatoire);
            $entityManager->flush();

            return $this->redirectToRoute('app_salle_operatoire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('salle_operatoire/new.html.twig', [
            'salle_operatoire' => $salleOperatoire,
            'form' => $form,
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_INFERMIER') or is_granted('ROLE_MEDECIN') or is_granted('ROLE_SECRETAIRE')"))]
    #[Route('/{id}', name: 'app_salle_operatoire_show', methods: ['GET'])]
    public function show(SalleOperatoire $salleOperatoire): Response
    {
        return $this->render('salle_operatoire/show.html.twig', [
            'salle_operatoire' => $salleOperatoire,
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_SECRETAIRE')"))]
    #[Route('/{id}/edit', name: 'app_salle_operatoire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SalleOperatoire $salleOperatoire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SalleOperatoireType::class, $salleOperatoire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_salle_operatoire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('salle_operatoire/edit.html.twig', [
            'salle_operatoire' => $salleOperatoire,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_salle_operatoire_delete', methods: ['POST'])]
    public function delete(Request $request, SalleOperatoire $salleOperatoire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$salleOperatoire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($salleOperatoire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_salle_operatoire_index', [], Response::HTTP_SEE_OTHER);
    }
}
