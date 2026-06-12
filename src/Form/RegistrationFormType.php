<?php

namespace App\Form;

use App\Entity\Fighter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'test@test.com'
                ]
            ])
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'TEST'
                ]
            ])
            ->add('first_name', TextType::class, [
                'required' => true,
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Test'
                ]
            ])
            ->add('birth_date', DateType::class, [
                'required' => true,
                'label' => 'Date de naissance',
                'attr' => [
                    'placeholder' => '01/02/2026'
                ]
            ])
            ->add('address', AddressFormType::class)
            ->add('social_secu', IntegerType::class, [
                'required' => true,
                'label' => 'Numéro de sécurité social',
                'attr' => [
                    'placeholder' => '12345678910'
                ],
                'constraints' => [
                    'length' => new Length(['min' => 13, 'max' => 13]),
                ]
            ])
            ->add('accred_cerfa', IntegerType::class, [
                'required' => true,
                'label' => 'Accréditation CERFA',
                'attr' => [
                    'placeholder' => '123456'
                ],
                'constraints' => [
                    'length' => new Length(['min' => 5, 'max' => 6]),
                ]
            ])
            ->add('pseudo', TextType::class, [
                'required' => true,
                'label' => 'Pseudonyme',
                'attr' => [
                    'placeholder' => 'Testeur'
                ]
            ])
            ->add('pokemon', ChoiceType::class, [
                'required' => true,
                'label' => 'Ton starter Pokemon préféré',
                'choices' => [
                    'Carapuce' => 1,
                    'Salamèche' => 2,
                    'Bulbizarre' => 3,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepter les termes',
                'constraints' => [
                    new IsTrue(
                        message: 'Vous devez accepter les termes et conditions',
                    ),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Fighter::class,
        ]);
    }
}
