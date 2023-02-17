<?php

namespace Up\Gateway\Persistence\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\Mapping\MappingException;
use Up\Core\Domain\Application\IApplicationRepository;
use Up\Core\Domain\Entities\Application;
use Up\Core\Domain\Entities\User;
use Up\Gateway\Persistence\IDatabase;

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

    }//end __construct()


    /**
     * @param  Application $application
     * @return integer
     */
    public function add(Application $application): int
    {
        try {
            $this->connection->clear();
            $application = $this->connection->merge($application);
            $this->connection->flush();
        } catch (OptimisticLockException | ORMException | MappingException $e) {
        }

        return $application->getApplicationId();

    }//end add()


    /**
     * @param  integer $applicationId
     * @return Application|null
     */
    public function find(int $applicationId): ?Application
    {
        $application = $this->repository->find($applicationId);

        if (!$application) {
            return null;
        }

        return $application;

    }//end find()


    /**
     * @param  integer $userId
     * @return Application[]
     */
    public function findByUserId(int $userId): array
    {
        return $this->repository->findBy(
            ['userId' => $userId],
            ['applicationId' => 'DESC']
        );

    }//end findByUserId()


    /**
     * @return array|Application
     */
    public function fetchAllActive(): array
    {
        return $this->repository->findBy([], ['applicationId' => 'DESC']);

    }//end fetchAllActive()


    /**
     * @param Application $application
     */
    public function update(Application $application): void
    {
        try {
            $this->connection->persist($application);
            $this->connection->flush();
        } catch (OptimisticLockException | ORMException $e) {
        }

    }//end update()


    /**
     * @param Application $application
     */
    public function delete(Application $application): void
    {
        try {
            $this->connection->remove($application);
        } catch (ORMException $e) {
        }

    }//end delete()


    /**
     * @param  integer $userId
     * @param  string  $fromDate
     * @param  string  $toDate
     * @return array
     */
    public function fetchAllBetweenDates(int $userId, string $fromDate, string $toDate): array
    {
        return $this->connection->createQueryBuilder()->select('e')->from(Application::class, 'e')->where('e.userId = :userId')->andWhere(':from BETWEEN e.fromDate AND e.toDate')->orWhere(':to BETWEEN e.fromDate AND e.toDate')->setParameter('from', date('Y-m-d', strtotime($fromDate)))->setParameter('to', date('Y-m-d', strtotime($toDate)))->setParameter('userId', $userId)->getQuery()->getResult();

    }//end fetchAllBetweenDates()


}//end class
