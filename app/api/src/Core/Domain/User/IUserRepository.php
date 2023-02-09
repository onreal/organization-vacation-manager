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
    public function findByOne(string $type, $value): ?User;

    /**
     * @param string $type
     * @param $value
     * @return array
     */
    public function findByAll(string $type, $value): array;

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
