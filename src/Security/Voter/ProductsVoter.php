<?php

namespace App\Security\Voter;

use App\Entity\Produit;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductsVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const DELETE='POST_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Produit;
    }
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($subject,$user);
            case self::DELETE:
                return $this->canDelete($subject,$user);
        }
        throw new \LogicException('This code should not be reached!');
    }

    private function canDelete(Produit $post, User $user): bool
    {
        // if they can edit, they can view
        if ($this->canEdit($post, $user)) {
            return true;
        }

        // the Post object could have, for example, a method `isPrivate()`
        return false;
    }

    private function canEdit(Produit $post, User $user): bool
    {
        // this assumes that the Post object has a `getOwner()` method
        return $user === $post->getUser();
    }
}

    

