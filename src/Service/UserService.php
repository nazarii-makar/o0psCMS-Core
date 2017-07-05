<?php

namespace o0psCore\Service;

use o0psCore\Entity\User;

/**
 * Class UserService
 * @package o0psCore\Service
 */
class UserService
{
    /**
     * Static function for checking hashed password (as required by Doctrine)
     *
     * @param User   $user          The identity object
     * @param string $passwordGiven Password provided to be verified
     *
     * @return boolean true if the password was correct, else, returns false
     */
    public static function verifyHashedPassword(User $user, $passwordGiven)
    {
        return password_verify($passwordGiven, $user->getPassword());
    }

    /**
     * Encrypt Password
     *
     * Creates a password hash
     *
     * @param String
     *
     * @return String
     */
    public static function encryptPassword($password)
    {
        $options = [
            'cost' => 10,
        ];

        return password_hash($password, PASSWORD_BCRYPT, $options);
    }
}
