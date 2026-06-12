<?php

namespace App\Command;

use App\Entity\Fighter;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Completion\CompletionInput;
use Symfony\Component\Mime\Address;

#[AsCommand(name: 'app:fighters:validate', description: 'Validation of all fighters')]
class FighterCommand
{
    public function __construct(private EmailVerifier $emailVerifier, private EntityManagerInterface $em)
    {
    }

    public function __invoke(): int
    {
        try{
            foreach($this->em->getRepository(Fighter::class)->findBy(['admin_valid' => '0']) as $fighter) {
                // generate a signed url and email it to the fighter
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $fighter,
                (new TemplatedEmail())
                ->from(new Address('guilhem.goulabert@gmail.com', 'FCP Goulabert'))
                ->to((string) $fighter->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('confirmation_email.html.twig')
                );

                // after sending the email, set the veirifcation value to true
                $fighter->setAdminValid(true);
                $this->em->persist($fighter);
                $this->em->flush();

                }
            return COMMAND::SUCCESS;
        }
        catch (Exception $e) {
            return COMMAND::FAILURE;
        }
    }
}