<?php

namespace Up\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Up\Core\Domain\LogAction\ILogActionService;

final class LogActionController
{
    /**
     * @var ILogActionService
     */
    private ILogActionService $logActionService;

    /**
     * @param ILogActionService $logActionService
     */
    public function __construct(ILogActionService $logActionService)
    {
        $this->logActionService = $logActionService;
    }

    public function get(Response $response, int $userId): Response
    {
        $serviceResponse = $this->logActionService->findByUserId($userId);
        $response->getBody()->write($serviceResponse->getResponse());
        return $response->withStatus($serviceResponse->getCode(), $serviceResponse->getMessage());
    }
}
