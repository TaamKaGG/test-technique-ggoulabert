<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class AddressFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('street', TextType::class, [
                'required' => true,
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => '1 rue du test'
                ]
            ])
            ->add('street_bis', TextType::class, [
                'required' => false,
                'label' => "Complément d'adresse",
            ])
            ->add('postal_code', IntegerType::class, [
                'required' => true,
                'label' => 'Code postal',
                'attr' => [
                    'placeholder' => '12345'
                ],
                'constraints' => [
                    'length' => new Length(['min' => 5, 'max' => 5]),
                ]
            ])
            ->add('city', TextType::class, [
                'required' => true,
                'label' => 'Ville',
                'attr' => [
                    'placeholder' => 'Test City'
                ]
            ])
            ->add('country', TextType::class, [
                'required' => true,
                'label' => 'Pays',
                'attr' => [
                    'placeholder' => 'Test Land'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
