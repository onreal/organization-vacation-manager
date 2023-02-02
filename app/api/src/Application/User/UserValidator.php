<?php

namespace Up\Application\User;

use ReflectionException;
use Up\Core\Domain\User\IUserValidator;
use Up\Core\Enum\Role;

final class UserValidator implements IUserValidator
{
    /**
     * @param string $email
     * @return bool
     */
    public function isAnEmail(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $domain
     * @return bool
     */
    public function isDomainMx(string $domain): bool
    {
        return checkdnsrr($domain, 'MX');
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isNameLength(string $name): bool
    {
        return (strlen($name) >= 3) && (strlen($name) <= 32);
    }

    /**
     * @param string $password
     * @return bool
     */
    public function isPasswordRequirements(string $password): bool
    {
        if (strlen($password) < 8) {
            return false;
        }
        $uppercase = preg_match('@[A-Z]@', $password);
        if (!$uppercase) {
            return false;
        }
        $lowercase = preg_match('@[a-z]@', $password);
        if (!$lowercase) {
            return false;
        }
        $number = preg_match('@[0-9]@', $password);
        if (!$number) {
            return false;
        }
        $special = preg_match('@\W@', $password);
        if (!$special) {
            return false;
        }

        return true;
    }

    /**
     * @param string $password
     * @param string $salt
     * @return bool
     */
    public function verifyPassword(string $password, string $salt): bool
    {
        return password_verify($password, $salt);
    }

    /**
     * @param $role
     * @return bool
     * @throws ReflectionException
     */
    public function roleIsValid($role): bool
    {
        return Role::isValidValue($role);
    }
}
