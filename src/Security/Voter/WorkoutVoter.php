<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\Workout;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class WorkoutVoter extends Voter
{
    public const VIEW = 'VIEW';
    public const EDIT = 'EDIT';

    /**
     * @param string $attribute
     * @param Workout $subject
     * @return bool
     */
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return
            in_array($attribute, [self::EDIT, self::VIEW], true) &&
            $subject instanceof Workout
        ;
    }

    /**
     * @param string $attribute
     * @param Workout $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::EDIT => $this->canEdit($subject, $user),
            self::VIEW => $this->canView($subject, $user),
            default => false,
        };
    }

    private function canEdit(Workout $subject, UserInterface $user): bool
    {
        if ($user instanceof User && $user->isClient()) {
            return $user->getClientWorkouts()->contains($subject);
        }

        // TODO add trainer logic here later

        return false;
    }

    private function canView(Workout $subject, UserInterface $user): bool
    {
        if ($user instanceof User && $user->isClient()) {
            return $user->getClientWorkouts()->contains($subject);
        }

        // TODO add trainer logic here later

        return false;
    }
}
