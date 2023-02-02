<?php

namespace Up\Core\Domain\Application;

use DateTime;
use Up\Core\Domain\Entities\Application;

interface IApplicationRepository
{
    /**
     * @param Application $application
     * @return int
     */
    public function add(Application $application): int;

    /**
     * @param int $applicationId
     * @return ?Application
     */
    public function find(int $applicationId): ?Application;

    /**
     * @param int $userId
     * @return Application[]
     */
    public function findByUserId(int $userId): array;

    /**
     * @return Application[]
     */
    public function fetchAllActive(): array;

    /**
     * @param int $userId
     * @param string $fromDate
     * @param string $toDate
     * @return array
     */
    public function fetchAllBetweenDates(int $userId, string $fromDate, string $toDate): array;

    /**
     * @param Application $application
     * @return void
     */
    public function update(Application $application): void;

    /**
     * @param Application $application
     * @return void
     */
    public function delete(Application $application): void;
}
