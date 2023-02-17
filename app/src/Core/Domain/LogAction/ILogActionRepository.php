<?php

namespace Up\Core\Domain\LogAction;

use Up\Core\Domain\Entities\LogAction;

interface ILogActionRepository
{
    /**
     * @param LogAction $logAction
     * @return int
     */
    public function add(LogAction $logAction): int;

    /**
     * @param int $userId
     * @return LogAction[]
     */
    public function findByUserId(int $userId): array;
}
