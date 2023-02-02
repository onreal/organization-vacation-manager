<?php

namespace Up\Application\Application;

use ReflectionException;
use Up\Core\Domain\Application\IApplicationRepository;
use Up\Core\Domain\Application\IApplicationValidator;
use Up\Core\Enum\ApplicationStatus;

final class ApplicationValidator implements IApplicationValidator
{
    /**
     * @var IApplicationRepository
     */
    private IApplicationRepository $applicationRepository;

    public function __construct(IApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    /**
     * @param string $fromDate
     * @param string $toDate
     * @return bool
     */
    public function isEndAfterStart(string $fromDate, string $toDate): bool
    {
        $fromDateTsm = strtotime($fromDate);
        $toDateTsm = strtotime($toDate);
        if ($fromDateTsm > $toDateTsm) {
            return false;
        }

        return true;
    }

    /**
     * @param string $fromDate
     * @return bool
     */
    public function isStartAfterNow(string $fromDate): bool
    {
        $fromDateTsm = strtotime($fromDate);
        $now = strtotime(date('d-m-y') . ' +1 day');
        if ($fromDateTsm < $now) {
            return false;
        }

        return true;
    }

    /**
     * @param int $userId
     * @param string $fromDate
     * @param string $toDate
     * @return bool
     */
    public function isDatesNotInRange(int $userId, string $fromDate, string $toDate): bool
    {
        $applications = $this->applicationRepository->fetchAllBetweenDates($userId, $fromDate, $toDate);
        if (!empty($applications)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $reason
     * @return bool
     */
    public function isReasonNotEmpty(string $reason): bool
    {
        if ($reason == null) {
            return false;
        }

        if (empty($reason)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $status
     * @return bool
     * @throws ReflectionException
     */
    public function isStatusValid(string $status): bool
    {
        return ApplicationStatus::isValidValue($status);
    }
}