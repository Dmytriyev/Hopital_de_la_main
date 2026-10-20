<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
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
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
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

            // Mot de passe : ! + dateNaissance(ddmmYYYY) + Nom(1 maj + 2 min)  ex: !15031985Mar
            $dateNaissance = $user->getDateNaissance()->format('dmY');
            $nom           = mb_strtoupper(mb_substr($user->getNom(), 0, 1))
                           . mb_strtolower(mb_substr($user->getNom(), 1, 2));
            $plainPassword = '!' . $dateNaissance . $nom;
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Matricule : ID en tête + padding aléatoire pour atteindre 6 chiffres
            // Exemple : id=7 → "7" + "00342" → 700342
            $idStr     = (string) $user->getId();
            $remaining = max(0, 6 - strlen($idStr));
            $random    = $remaining > 0
                ? str_pad((string) random_int(0, (int) str_repeat('9', $remaining)), $remaining, '0', STR_PAD_LEFT)
                : '';
            $user->setMatricule((int) ($idStr . $random));
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
