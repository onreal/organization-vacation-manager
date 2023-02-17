<?php

namespace Up\Core\Domain\LogAction;

use Up\Core\Model\ResponseModel;

interface ILogActionService
{
    /**
     * @param int $userId
     * @return ResponseModel
     */
    public function findByUserId(int $userId): ResponseModel;
}
