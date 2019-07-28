<?php

namespace App\Form;

use App\Entity\CarDealer;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class VehicleType extends AbstractType
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;


    /**
     * EditVehicleType constructor.
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vehicleType', EntityType::class, [
                'class' => \App\Entity\VehicleType::class,
                'choice_label' => 'label',
                'label' => 'Type de véhicule'
            ])
            ->add('brand', TextType::class, array('label' => 'Marque'))
            ->add('model', TextType::class, array('label' => 'Modèle'))
            ->add('serialNumber', TextType::class, array('label' => 'Numéro de série'))
            ->add('color', TextType::class, array('label' => 'Couleur'))
            ->add('autonomy', NumberType::class, array('label' => 'Autonomie'))
            ->add('dailyDistance', NumberType::class, array('label' => 'Distance par jour'))
            ->add('matriculation', TextType::class, array('label' => 'Plaque d\'immatriculation'))
            ->add('kilometers', NumberType::class, array('label' => 'Kilomètres'))
            ->add('purchasingDate', DateType::class, array('label' => 'Date d\'achat'))
            ->add('purchasingPrice', NumberType::class, array('label' => 'Prix d\'achat'))
            ->add('dailyPrice', NumberType::class, array('label' => 'Prix par jour'))
            ->add('minDailyPrice', NumberType::class, array('label' => 'Prix minimum par jour'));

        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $builder->add('carDealer', EntityType::class, [
                'class' => CarDealer::class,
                'choice_label' => 'name',
                'label' => 'Concessionaire'
            ]);
        }

        $builder
            ->add('state', ChoiceType::class, [
                'choices' => ['Afficher' => true, 'Masquer' => false],
                'label' => ' '
            ])
            ->add('submit', SubmitType::class, array('label' => 'Valider'));
    }

    public
    function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
