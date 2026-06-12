<?php

namespace App\Controller;

use App\Entity\Fighter;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier, private EntityManagerInterface $em)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request)
    {
        $fighter = new Fighter();
        $form = $this->createForm(RegistrationFormType::class, $fighter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fighter->setAdminValid(0);
            $this->em->persist($fighter);
            $this->em->flush();

            return $this->render('register_done.html.twig');
            // return $this->redirectToRoute('app_register');
        }
        
        return $this->render('security/signup.html.twig', [
            'registrationForm' => $form->createView(),
            'formErrors' => $form->getErrors(true),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, VerifyEmailHelperInterface $verifyEmailHelper): Response
    {
        // validate email confirmation link, sets fighter::isVerified=true and persists
        try {
            /** @var Fighter $fighter */
            $fighter = $this->em->getRepository(Fighter::class)->find($request->query->get('id'));
            $verifyEmailHelper->validateEmailConfirmationFromRequest(
                $request,
                $fighter->getId(),
                $fighter->getEmail(),
            );

            $fighter->setIsVerified(true);

            $this->em->persist($fighter);
            $this->em->flush();

            return $this->redirectToRoute('app_create_password', ['fighterId' => $fighter->getId()]);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }
    }

    #[Route('finish_it', name: 'app_create_password')]
    public function createPassword(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security): Response
    {
        $fighter = new Fighter();
        // $fighter = $this->em->getRepository(Fighter::class)->findAll()[0];
        $passForm = $this->createFormBuilder($fighter)
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(
                        message: 'Please enter a password',
                    ),
                    new Length(
                        min: 8,
                        minMessage: 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        max: 4096,
                    ),
                ],
            ])
            ->getForm()
        ;
        $passForm->handleRequest($request);

        if ($passForm->isSubmitted() && $passForm->isValid()) {
            $fighter = $this->em->getRepository(Fighter::class)->find($request->query->get('fighterId'));
            /** @var string $plainPassword */
            $plainPassword = $passForm->get('plainPassword')->getData();

            // encode the plain password
            $fighter->setPassword($userPasswordHasher->hashPassword($fighter, $plainPassword));

            $this->em->persist($fighter);
            $this->em->flush();
            
            $security->login($fighter);

            return $this->redirectToRoute('home');
        }

        return $this->render('security/pass.html.twig', [
            'passForm' => $passForm,
        ]);
    }
}
