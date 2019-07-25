<?php

namespace App\Form;

use App\Entity\Vehicle;
use Doctrine\DBAL\Types\DateTimeType;
use Faker\Provider\cs_CZ\DateTime;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditVehicleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand',TextType::class, array('label' => 'Marque'))
            ->add('model',TextType::class, array('label' => 'Modèle'))
            ->add('serialNumber',TextType::class, array('label' => 'Numéro de série'))
            ->add('color',TextType::class, array('label' => 'Couleur'))
            ->add('autonomy',NumberType::class, array('label' => 'Autonomie'))
            ->add('dailyDistance',NumberType::class, array('label' => 'Distance par jour'))
            ->add('matriculation',TextType::class, array('label' => 'Plaque d\'immatriculation'))
            ->add('kilometers',NumberType::class, array('label' => 'Kilomètres'))
            ->add('purchasingDate',DateType::class, array('label' => 'Date d\'achat'))
            ->add('state',Boolean::class, array('label' => 'Etat'))
            ->add('minDailyPrice', NumberType::class, array('label' => 'Prix minimum par jour'))
            ->add('purchasingPrice', NumberType::class, array('label' => 'Prix d\'achat'))
            ->add('dailyPrice', NumberType::class, array('label' => 'Prix par jour'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
