<?php

namespace App\EntityListener;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener
{

    private UserPasswordHasherInterface $passwordHasherInterface;

    public function __construct(UserPasswordHasherInterface $passwordHasherInterface)
    {
        $this->passwordHasherInterface = $passwordHasherInterface;
    }

    /**
     * Listen the changes of the User before persist
     *
     * @param User $user
     * @return void
     */
    public function prePersist(User $user)
    {
        $this->encodePassword($user);
    }

    /**
     * Listen the changes of the User before update
     *
     * @param User $user
     * @return void
     */
    public function preUpdate(User $user)
    {
        $this->encodePassword($user);
    }


    /**
     * Encode the user password
     *
     * @param User $user
     * @return void
     */
    public function encodePassword(User $user)
    {
        if ($user->getPlainPassword() === null) {
            return;
        } else {
            $user->setPassword($this->passwordHasherInterface->hashPassword($user, $user->getPlainPassword()));
        }

        // $user->setPlainPassword(null);
    }
}
