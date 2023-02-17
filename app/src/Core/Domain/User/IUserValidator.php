<?php

namespace Up\Core\Domain\User;

interface IUserValidator
{
    /**
     * @param string $email
     * @return bool
     */
    public function isAnEmail(string $email): bool;

    /**
     * @param string $domain
     * @return bool
     */
    public function isDomainMx(string $domain): bool;

    /**
     * @param string $name
     * @return bool
     */
    public function isNameLength(string $name): bool;

    /**
     * @param string $password
     * @return bool
     */
    public function isPasswordRequirements(string $password): bool;

    /**
     * @param string $password
     * @param string $salt
     * @return bool
     */
    public function verifyPassword(string $password, string $salt): bool;

    /**
     * @param $role
     * @return bool
     */
    public function roleIsValid($role): bool;
}
