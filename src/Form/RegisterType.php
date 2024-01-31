<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ["required" => true, "label" => "Email: "])
            ->add("first_name", TextType::class, ["required" => true, "label" => "First Name: "])
            ->add("last_name", TextType::class, ["required" => true, "label" => "Second Name:"])
            ->add('country_number', ChoiceType::class, [
                "label" => "Country: ",
                'required' => true, 
                'choices' => [
                    'Poland(+48)' => '48',
                    'Ukraine(+380)' => '380',
                ],
            ])
            ->add('phone', NumberType::class, [
                "required" => true, 
                "label" => "Phone",
                "attr" => [
                    "pattern" => ".{9,9}",
                    "title" => "9 numbers minimum",
                    "maxlength" => "9",
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => false,
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Password: '],
                'second_options' => ['label' => 'Repeat Password: '],
            ])
            ->add('register', SubmitType::class, ['label' => 'Register'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'=> User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'user_id',
        ]);
    }
}
