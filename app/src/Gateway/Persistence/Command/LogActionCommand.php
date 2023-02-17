<?php

namespace Up\Gateway\Persistence\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Up\Core\Domain\Entities\LogAction;
use Up\Core\Domain\LogAction\ILogActionRepository;
use Up\Gateway\Persistence\IDatabase;

final class LogActionCommand implements ILogActionRepository
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
        $this->repository = $connection->getRepository(LogAction::class);

    }//end __construct()


    /**
     * @param  LogAction $logAction
     * @return integer
     */
    public function add(LogAction $logAction): int
    {
        try {
            $this->connection->persist($logAction);
            $this->connection->flush();
        } catch (OptimisticLockException | ORMException $e) {
        }

        return $logAction->getLogActionId();

    }//end add()


    /**
     * @param  integer $userId
     * @return LogAction[]
     */
    public function findByUserId(int $userId): array
    {
        $logActions = $this->repository->findBy(
            ['userId' => $userId],
            ['logActionId' => 'DESC']
        );

        if (!$logActions) {
            return [];
        }

        return $logActions;

    }//end findByUserId()


}//end class
