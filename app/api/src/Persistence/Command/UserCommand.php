<?php

namespace Up\Persistence\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Up\Core\Domain\Entities\User;
use Up\Core\Domain\User\IUserRepository;
use Up\Persistence\IDatabase;

final class UserCommand implements IUserRepository
{

    /**
     * @var EntityManager
     */
    private EntityManager $connection;

    /**
     * @var EntityRepository
     */
    private EntityRepository $repository;

    /**
     * @param IDatabase $connection
     */
    public function __construct(IDatabase $connection)
    {
        $this->connection = $connection->getConnection();
        $this->repository = $connection->getRepository(User::class);
    }

    /**
     * @param User $user
     * @return int
     */
    public function add(User $user): int
    {
        try {
            $this->connection->persist($user);
            $this->connection->flush();
        } catch (OptimisticLockException|ORMException $e) {
        }

        return $user->getUserId();
    }

    public function findByOne(string $type, $value): ?User
    {
        if (ctype_digit($value)) {
            $value = (int) $value;
        }

        $user = $this->repository->findOneBy([$type => $value]);

        if (!$user) {
            return null;
        }

        return $user;
    }

    public function findByAll(string $type, $value): array
    {
        if (ctype_digit($value)) {
            $value = (int) $value;
        }

        $user = $this->repository->findBy([$type => $value]);

        if (!$user) {
            return [];
        }

        return $user;
    }

    /**
     * @return User[]
     */
    public function fetchAll(): array
    {
        $users = $this->repository->findBy([], ['userId' => 'DESC']);

        if (!$users) {
            return [];
        }

        return $users;
    }

    /**
     * @param User $user
     */
    public function update(User $user): void
    {
        try {
            $this->connection->persist($user);
            $this->connection->flush();
        } catch (OptimisticLockException|ORMException $e) {
        }
    }

    /**
     * @param User $user
     */
    public function delete(User $user): void
    {
        try {
            $this->connection->remove($user);
            $this->connection->flush();
        } catch (ORMException $e) {
        }
    }
}
