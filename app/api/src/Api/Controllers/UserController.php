<?php

namespace Up\Api\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Up\Core\Domain\User\IUserService;

final class UserController
{
    /**
     * @var IUserService
     */
    private IUserService $userService;

    /**
     * @param IUserService $userService
     */
    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function post(Request $request, Response $response): Response
    {
        $serviceResponse = $this->userService->add($request->getBody());
        $response->getBody()->write($serviceResponse->getResponse());
        return $response->withStatus($serviceResponse->getCode(), $serviceResponse->getMessage());
    }

    public function get(Response $response, int $userId): Response
    {
        $serviceResponse = $this->userService->find($userId);
        $response->getBody()->write($serviceResponse->getResponse());
        return $response->withStatus($serviceResponse->getCode(), $serviceResponse->getMessage());
    }

    public function getAll(Response $response): Response
    {
        $serviceResponse = $this->userService->fetchAll();
        $response->getBody()->write($serviceResponse->getResponse());
        return $response->withStatus($serviceResponse->getCode(), $serviceResponse->getMessage());
    }

    public function put(Request $request, Response $response, int $userId): Response
    {
        $serviceResponse = $this->userService->update($userId, $request->getBody());
        $response->getBody()->write($serviceResponse->getResponse());
        return $response->withStatus($serviceResponse->getCode(), $serviceResponse->getMessage());
    }

    public function delete(Response $response, int $userId): Response
    {
        $serviceResponse = $this->userService->delete($userId);
        $response->getBody()->write($serviceResponse->getResponse());
        return $response->withStatus($serviceResponse->getCode(), $serviceResponse->getMessage());
    }

    public function login(Request $request, Response $response): Response
    {
        $serviceResponse = $this->userService->login($request->getBody());
        $response->getBody()->write($serviceResponse->getResponse());
        return $response->withStatus($serviceResponse->getCode(), $serviceResponse->getMessage());
    }
}
