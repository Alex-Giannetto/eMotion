<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array('label' => 'Mail'))
            ->add('firstname', TextType::class, array('label' => 'Prénom'))
            ->add('lastname', TextType::class, array('label' => 'Nom'))
            ->add('birthDate', BirthdayType::class, array('label' => 'Date de naissance'))
            ->add('phoneNumber', TextType::class, array('label' => 'Numéro de téléphone'))
            ->add('address', TextType::class, array('label' => 'Adresse'))
            ->add('city', TextType::class, array('label' => 'Ville'))
            ->add('zipCode', TextType::class, array('label' => 'Code Postal'))
            ->add('driverLicense', TextType::class, array('label' => 'Permis de conduire'))
            ->add('submit', SubmitType::class, array('label' => 'Valider'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
