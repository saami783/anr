<?php

namespace App\Security\Voter;

use App\Entity\City;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CityVoter extends Voter
{
    const EDIT = 'EDIT';
    const NEW = 'NEW';
    const DELETE = 'DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::NEW, self::DELETE]) && $subject instanceof City;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::EDIT => $this->canEdit($user),
            self::NEW => $this->canCreate($user),
            self::DELETE => $this->canDelete($user),
            default => false,
        };

    }

    private function canEdit(UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }

    private function canCreate(UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }

    private function canDelete(UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }
}
