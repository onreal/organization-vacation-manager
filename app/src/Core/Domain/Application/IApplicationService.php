<?php

namespace Up\Core\Domain\Application;

use Up\Application\Models\ResponseModel;

interface IApplicationService
{
    /**
     * @param string $payload
     * @return ResponseModel
     */
    public function add(string $payload): ResponseModel;

    /**
     * @param int $applicationId
     * @return ResponseModel
     */
    public function find(int $applicationId): ResponseModel;

    /**
     * @param int $userId
     * @return ResponseModel
     */
    public function findByUserId(int $userId): ResponseModel;

    /**
     * @return ResponseModel
     */
    public function fetchAll(): ResponseModel;

    /**
     * @param int $applicationId
     * @param string $status
     * @return ResponseModel
     */
    public function updateStatus(int $applicationId, string $status): ResponseModel;

    /**
     * @param int $applicationId
     * @param string $payload
     * @return ResponseModel
     */
    public function update(int $applicationId, string $payload): ResponseModel;

    /**
     * @param int $applicationId
     * @return ResponseModel
     */
    public function delete(int $applicationId): ResponseModel;
}
