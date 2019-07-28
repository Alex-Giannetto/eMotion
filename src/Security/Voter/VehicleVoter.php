<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class VehicleVoter extends Voter
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var VehicleRepository
     */
    private $vehicleRepository;


    /**
     * VehicleVoter constructor.
     */
    public function __construct(Security $security, VehicleRepository $vehicleRepository)
    {
        $this->security = $security;
        $this->vehicleRepository = $vehicleRepository;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['VEHICLE_EDIT', 'VEHICLE_VIEW', 'VEHICLE_DELETE'])
            && $subject instanceof \App\Entity\Vehicle;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if (!$this->security->isGranted('ROLE_EMPLOYEE')) {
            return false;
        }

        switch ($attribute) {
            case 'VEHICLE_EDIT':
                return $this->canView($user, $subject);

            case 'VEHICLE_VIEW':
                return $this->canView($user, $subject);

            case 'VEHICLE_DELETE':
                return $this->canView($user, $subject);

            default:
                return false;
        }
    }


    function canView(User $user, Vehicle $vehicle): bool
    {
        $vehicles = $this->vehicleRepository->findBy(['carDealer' => $user->getCarDealer()->getId()]);
        return in_array($vehicle, $vehicles);
    }


}
