<?php

namespace App\Controller;

use App\Entity\Specialite;
use App\Form\SpecialiteType;
use App\Repository\SpecialiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/specialite')]
final class SpecialiteController extends AbstractController
{
    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_ADMIN')"))]
    #[Route(name: 'app_specialite_index', methods: ['GET'])]
    public function index(SpecialiteRepository $specialiteRepository): Response
    {
        return $this->render('specialite/index.html.twig', [
            'specialites' => $specialiteRepository->findAll(),
        ]);
    }

    #[IsGranted(new Expression("is_granted('ROLE_MEDECIN') or is_granted('ROLE_ADMIN')"))]
    #[Route('/new', name: 'app_specialite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $specialite = new Specialite();
        $form = $this->createForm(SpecialiteType::class, $specialite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($specialite);
            $entityManager->flush();

            return $this->redirectToRoute('app_specialite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('specialite/new.html.twig', [
            'specialite' => $specialite,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_MEDECIN')]
    #[Route('/{id}', name: 'app_specialite_show', methods: ['GET'])]
    public function show(Specialite $specialite): Response
    {
        return $this->render('specialite/show.html.twig', [
            'specialite' => $specialite,
        ]);
    }

    #[IsGranted('ROLE_MEDECIN')]
    #[Route('/{id}/edit', name: 'app_specialite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Specialite $specialite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SpecialiteType::class, $specialite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_specialite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('specialite/edit.html.twig', [
            'specialite' => $specialite,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_specialite_delete', methods: ['POST'])]
    public function delete(Request $request, Specialite $specialite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$specialite->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($specialite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_specialite_index', [], Response::HTTP_SEE_OTHER);
    }
}
