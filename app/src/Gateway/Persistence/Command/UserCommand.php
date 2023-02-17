<?php

namespace Up\Gateway\Persistence\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Up\Core\Domain\Entities\User;
use Up\Core\Domain\User\IUserRepository;
use Up\Gateway\Persistence\IDatabase;

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

    }//end __construct()


    /**
     * @param  User $user
     * @return integer
     */
    public function add(User $user): int
    {
        try {
            $this->connection->persist($user);
            $this->connection->flush();
        } catch (OptimisticLockException | ORMException $e) {
        }

        return $user->getUserId();

    }//end add()


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

    }//end findByOne()


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

    }//end findByAll()


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

    }//end fetchAll()


    /**
     * @param User $user
     */
    public function update(User $user): void
    {
        try {
            $this->connection->persist($user);
            $this->connection->flush();
        } catch (OptimisticLockException | ORMException $e) {
        }

    }//end update()


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

    }//end delete()


}//end class
