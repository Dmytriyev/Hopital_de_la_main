<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\UserCredentialGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class RegistrationController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserCredentialGenerator $credentialGenerator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ROLE_USER toujours présent
            $roles = $user->getRoles();
            if (!in_array('ROLE_USER', $roles)) {
                $roles[] = 'ROLE_USER';
                $user->setRoles($roles);
            }
            $user->setCreatedAt(new \DateTimeImmutable('now'));
            // Mot de passe temporaire pour obtenir l'ID après le premier flush
            $user->setPassword($userPasswordHasher->hashPassword($user, bin2hex(random_bytes(16))));
            $entityManager->persist($user);
            $entityManager->flush();

            // Mot de passe et matricule : logique centralisée dans UserCredentialGenerator
            $plainPassword = $credentialGenerator->generatePlainPassword($user);
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setMatricule($credentialGenerator->generateMatricule($user->getId()));
            $entityManager->flush();

            $this->addFlash('success', sprintf(
                'Utilisateur créé. Mot de passe généré : %s (à communiquer à l\'utilisateur)',
                $plainPassword
            ));

            return $this->redirectToRoute('app_home', []);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
