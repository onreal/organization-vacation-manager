<?php

namespace Up\Persistence\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\Mapping\MappingException;
use Up\Core\Domain\Application\IApplicationRepository;
use Up\Core\Domain\Entities\Application;
use Up\Core\Domain\Entities\User;
use Up\Persistence\IDatabase;

final class ApplicationCommand implements IApplicationRepository
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
        $this->repository = $connection->getRepository(Application::class);
    }

    /**
     * @param Application $application
     * @return int
     */
    public function add(Application $application): int
    {
        try {
            $this->connection->clear();
            $application = $this->connection->merge($application);
            $this->connection->flush();
        } catch (OptimisticLockException|ORMException|MappingException $e) {
        }

        return $application->getApplicationId();
    }

    /**
     * @param int $applicationId
     * @return Application|null
     */
    public function find(int $applicationId): ?Application
    {
        $application = $this->repository->find($applicationId);

        if (!$application) {
            return null;
        }

        return $application;
    }

    /**
     * @param int $userId
     * @return Application[]
     */
    public function findByUserId(int $userId): array
    {
        return $this->repository->findBy(
            ['userId' => $userId],
            ['applicationId' => 'DESC']
        );
    }

    /**
     * @return array|Application
     */
    public function fetchAllActive(): array
    {
        return $this->repository->findBy([], ['applicationId' => 'DESC']);
    }

    /**
     * @param Application $application
     */
    public function update(Application $application): void
    {
        try {
            $this->connection->persist($application);
            $this->connection->flush();
        } catch (OptimisticLockException|ORMException $e) {
        }
    }

    /**
     * @param Application $application
     */
    public function delete(Application $application): void
    {
        try {
            $this->connection->remove($application);
        } catch (ORMException $e) {
        }
    }

    /**
     * @param int $userId
     * @param string $fromDate
     * @param string $toDate
     * @return array
     */
    public function fetchAllBetweenDates(int $userId, string $fromDate, string $toDate): array
    {
        return $this->connection->createQueryBuilder()
            ->select('e')
            ->from(Application::class, 'e')
            ->where('e.fromDate BETWEEN :from AND :to')
            ->andWhere('e.toDate BETWEEN :from AND :to')
            ->andWhere('e.userId = :userId')
            ->setParameter('from', date('Y-m-d', strtotime($fromDate)))
            ->setParameter('to', date('Y-m-d', strtotime($toDate)))
            ->setParameter('userId', $userId)
            ->getQuery()->getResult();
    }
}
