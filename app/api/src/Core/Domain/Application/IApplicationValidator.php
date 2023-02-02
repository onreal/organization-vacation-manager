<?php

namespace Up\Core\Domain\Application;

interface IApplicationValidator
{
    /**
     * @param string $fromDate
     * @param string $toDate
     * @return bool
     */
    public function isEndAfterStart(string $fromDate, string $toDate): bool;

    /**
     * @param string $fromDate
     * @return bool
     */
    public function isStartAfterNow(string $fromDate): bool;

    /**
     * @param int $userId
     * @param string $fromDate
     * @param string $toDate
     * @return bool
     */
    public function isDatesNotInRange(int $userId, string $fromDate, string $toDate): bool;

    /**
     * @param string $reason
     * @return bool
     */
    public function isReasonNotEmpty(string $reason): bool;

    /**
     * @param string $status
     * @return bool
     */
    public function isStatusValid(string $status): bool;
}
