<?php

namespace Up\Application\LogAction;

use Up\Core\Domain\LogAction\ILogActionRepository;
use Up\Core\Domain\LogAction\ILogActionService;
use Up\Core\Domain\User\IUserRepository;
use Up\Core\Model\ResponseModel;

final class LogActionService implements ILogActionService
{
    /**
     * @var ILogActionRepository
     */
    private ILogActionRepository $logActionRepository;

    /**
     * @var IUserRepository
     */
    private IUserRepository $userRepository;

    /**
     * @param ILogActionRepository $logActionRepository
     * @param IUserRepository $userRepository
     */
    public function __construct(ILogActionRepository $logActionRepository, IUserRepository $userRepository)
    {
        $this->logActionRepository = $logActionRepository;
        $this->userRepository = $userRepository;
    }

    public function findByUserId(int $userId): ResponseModel
    {
        $user = $this->userRepository->find($userId);
        if (!$userId) {
            return new ResponseModel(400, 'user could not found');
        }

        if ($user->getRole() !== 'admin') {
            return new ResponseModel(400, 'activity log can provided only for admin users');
        }

        $logActions = $this->logActionRepository->findByUserId($userId);

        return new ResponseModel(200, 'OK', json_encode($logActions));
    }
}
