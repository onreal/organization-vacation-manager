<?php

namespace Up\Core\Domain\User;

use Up\Core\Model\ResponseModel;

interface IUserService
{
    /**
     * @param string $payload
     * @return ResponseModel
     */
    public function add(string $payload): ResponseModel;

    /**
     * @param int $userId
     * @return ResponseModel
     */
    public function find(int $userId): ResponseModel;

    /**
     * @return ResponseModel
     */
    public function fetchAll(): ResponseModel;

    /**
     * @param int $userId
     * @param string $payload
     * @return ResponseModel
     */
    public function update(int $userId, string $payload): ResponseModel;

    /**
     * @param string $credentials
     * @return ResponseModel
     */
    public function login(string $credentials): ResponseModel;

    /**
     * @param int $userId
     * @return ResponseModel
     */
    public function delete(int $userId): ResponseModel;
}
