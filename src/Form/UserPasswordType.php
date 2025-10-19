<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('actualPassword', PasswordType::class, [
          'mapped' => false,
          'required' => true,
          'label' => 'Actual Password',
          'constraints' => [
            new NotBlank([
              'message' => 'Please enter your actual password',
            ]),
          ],
          'attr' => [
            'autocomplete' => 'current-password',
          ],
        ])
        ->add('password', RepeatedType::class, [
          'type' => PasswordType::class,
          'mapped' => false,
          'invalid_message' => 'The password fields must match.',
          'options' => ['attr' => ['class' => 'password-field']],
          'required' => true,
          'first_options'  => ['label' => 'Password'],
          'second_options' => ['label' => 'Repeat Password'],
          'constraints' => [
            new NotBlank([
              'message' => 'Please enter a password',
            ]),
            new Length([
              'min' => 8,
              'max' => 4096,
              'minMessage' => 'Your password should be at least {{ limit }} characters',
            ]),
            new Regex([
              'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).+$/',
              'message' => 'The password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character',
            ]),
          ],
      ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
