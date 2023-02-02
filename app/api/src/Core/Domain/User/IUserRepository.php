<?php

namespace Up\Core\Domain\User;

use Up\Core\Domain\Entities\User;

interface IUserRepository
{
    /**
     * @param User $user
     * @return void
     */
    public function add(User $user): int;

    /**
     * @param string $type
     * @param mixed $value
     * @return User|null
     */
    public function findBy(string $type, $value): ?User;

    /**
     * @return User[]
     */
    public function fetchAll(): array;

    /**
     * @param User $user
     * @return void
     */
    public function update(User $user): void;

    /**
     * @param User $user
     * @return void
     */
    public function delete(User $user): void;
}
