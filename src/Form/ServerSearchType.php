<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class ServerSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('storage', TextType::class, [
                'required' => false,
            ])
            ->add('ram', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Regex("/^((\d+\w{2})(,\d+\w{2})*)?$/")
                ],
            ])
            ->add('hard_disk_type', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Choice(['SAS', 'SATA', 'SATA2', 'SSD']),
                ]
            ])
            ->add('location', TextType::class, [
                'required' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
