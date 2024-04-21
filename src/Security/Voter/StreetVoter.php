<?php

namespace App\Security\Voter;

use App\Entity\Street;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class StreetVoter extends Voter
{
    const EDIT = 'EDIT';
    const NEW = 'NEW';
    const DELETE = 'DELETE';
    const VIEW = 'VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::NEW, self::DELETE, self::VIEW])
            && $subject instanceof Street;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Street $street */
        $street = $subject;

        return match ($attribute) {
            self::EDIT => $this->canEdit($street, $user),
            self::NEW => $this->canCreate($street, $user),
            self::DELETE => $this->canDelete($street, $user),
            default => false,
        };

    }

    private function canEdit(Street $street, UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }

    private function canCreate(Street $street, UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }

    private function canDelete(Street $street, UserInterface $user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }

}
