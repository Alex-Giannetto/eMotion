<?php


namespace App\Security\Voter;


use App\Entity\Rental;
use App\Entity\User;
use DateTime;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class RentalVoter extends Voter
{

    public const VIEW = 'RENTAL_VIEW';
    public const DELETE = 'RENTAL_DELETE';
    /**
     * @var Security
     */
    private $security;

    /**
     * RentalVoter constructor.
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::VIEW, self::DELETE]) && $subject instanceof \App\Entity\Rental;
    }


    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        // TODO: Implement voteOnAttribute() method.

        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        if (!$this->security->isGranted('ROLE_USER')) {
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($user, $subject);


            case self::DELETE:
                return $this->canDelete($user, $subject);

            default:
                return false;
        }
    }


    private function canView(User $user, Rental $rental): bool
    {
        return $rental->getClient() === $user || $rental->getVehicle()->getCarDealer() === $user->getCarDealer();
    }

    private function canDelete(User $user, Rental $rental): bool
    {
        if ($this->security->isGranted('ROLE_EMPLOYEE')) {
            return $rental->getVehicle()->getCarDealer() === $user->getCarDealer();
        }


        return $rental->getClient() === $user && $rental->getStartRentalDate() < new DateTime('now');


    }
}