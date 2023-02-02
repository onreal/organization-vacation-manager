<?php

namespace Up\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Up\Core\Domain\Application\IApplicationService;

final class ApplicationController
{
    /**
     * @var IApplicationService
     */
    private IApplicationService $applicationService;

    /**
     * @param IApplicationService $applicationService
     */
    public function __construct(IApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function post(Request $request, Response $response): Response
    {
        $serviceResponse = $this->applicationService->add($request->getBody());
        $response->getBody()->write($serviceResponse->getResponse());
        return $response->withStatus($serviceResponse->getCode(), $serviceResponse->getMessage());
    }

    public function get(Response $response, int $applicationId): Response
    {
        $serviceResponse = $this->applicationService->find($applicationId);
        $response->getBody()->write($serviceResponse->getResponse());
        return $response->withStatus($serviceResponse->getCode(), $serviceResponse->getMessage());
    }

    public function put(Request $request, Response $response, int $applicationId): Response
    {
        $serviceResponse = $this->applicationService->update($applicationId, $request->getBody());
        $response->getBody()->write($serviceResponse->getResponse());
        return $response->withStatus($serviceResponse->getCode(), $serviceResponse->getMessage());
    }

    public function patch(Request $request, Response $response, int $applicationId): Response
    {
        $serviceResponse = $this->applicationService->updateStatus($applicationId, $request->getBody());
        $response->getBody()->write($serviceResponse->getResponse());
        return $response->withStatus($serviceResponse->getCode(), $serviceResponse->getMessage());
    }

    public function delete(Response $response, int $applicationId): Response
    {
        $serviceResponse = $this->applicationService->delete($applicationId);
        $response->getBody()->write($serviceResponse->getResponse());
        return $response->withStatus($serviceResponse->getCode(), $serviceResponse->getMessage());
    }
}
