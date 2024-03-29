<?php

namespace Up\Application\Domain\Application;

use DateTime;
use Throwable;
use Up\Application\Models\ResponseModel;
use Up\Application\Templates\MailTemplate;
use Up\Core\Domain\Application\IApplicationRepository;
use Up\Core\Domain\Application\IApplicationService;
use Up\Core\Domain\Application\IApplicationValidator;
use Up\Core\Domain\Entities\Application;
use Up\Core\Domain\LogAction\ILogActionRepository;
use Up\Core\Domain\User\IUserRepository;
use Up\Core\Domain\User\RoleEnum;
use Up\Core\External\IMailService;

final class ApplicationService implements IApplicationService
{

    /**
     * @var IApplicationRepository
     */
    private IApplicationRepository $applicationRepository;

    /**
     * @var IUserRepository
     */
    private IUserRepository $userRepository;

    /**
     * @var IApplicationValidator
     */
    private IApplicationValidator $applicationValidator;

    /**
     * @var ILogActionRepository
     */
    private ILogActionRepository $logActionRepository;

    /**
     * @var IMailService
     */
    private IMailService $mailService;


    /**
     * @param IApplicationRepository $applicationRepository
     * @param IApplicationValidator  $applicationValidator
     * @param IUserRepository        $userRepository
     * @param IMailService           $mailService
     * @param ILogActionRepository   $logActionRepository
     */
    public function __construct(
        IApplicationRepository $applicationRepository,
        IApplicationValidator $applicationValidator,
        IUserRepository $userRepository,
        IMailService $mailService,
        ILogActionRepository $logActionRepository
    ) {
        $this->applicationRepository = $applicationRepository;
        $this->applicationValidator  = $applicationValidator;
        $this->userRepository        = $userRepository;
        $this->mailService           = $mailService;
        $this->logActionRepository   = $logActionRepository;

    }//end __construct()


    /**
     * @param  string $payload
     * @return ResponseModel
     */
    public function add(string $payload): ResponseModel
    {
        try {
            $errors      = [];
            $application = $this->validate($errors, $payload);
            if (!empty($errors)) {
                $code = array_keys($errors)[0];
                return new ResponseModel($code, $errors[$code]);
            }

            if (!$this->applicationValidator->isStatusValid($application->status)) {
                return new ResponseModel(400, 'status is not valid');
            }

            $user = $this->userRepository->findByOne('userId', $application->userId);
            if (!$user) {
                return new ResponseModel(400, 'user is not valid');
            }

            $fromDate = new DateTime($application->fromDate);
            $toDate   = new DateTime($application->toDate);

            $newApplication = new Application();
            $newApplication->setUserId($user->getUserId());
            $newApplication->setStatus($application->status);
            $newApplication->setFromDate($fromDate);
            $newApplication->setToDate($toDate);
            $newApplication->setReason($application->reason);
            $newApplication->setUser($user);
            $applicationId = $this->applicationRepository->add($newApplication);

            $admins = $this->userRepository->findByAll('role', RoleEnum::ADMIN);
            if (!empty($admins)) {
                $subject  = 'New vacation entry submitted';
                $template = MailTemplate::adminTemplate(
                    $applicationId,
                    $user->getFirstName().' '.$user->getLastName(),
                    $application->fromDate,
                    $application->toDate,
                    $application->reason
                );
                foreach ($admins as $admin) {
                    $this->mailService->send(
                        $admin->getEmail(),
                        $subject,
                        $template
                    );
                }
            }

            return new ResponseModel(201, 'OK', $this->applicationRepository->find($applicationId));
        } catch (Throwable $e) {
            return new ResponseModel(500, $e->getMessage());
        }//end try

    }//end add()


    /**
     * @param  integer $applicationId
     * @return ResponseModel
     */
    public function find(int $applicationId): ResponseModel
    {
        try {
            $application = $this->applicationRepository->find($applicationId);
            if (!$application) {
                return new ResponseModel(404, 'application not found');
            }

            return new ResponseModel(200, 'OK', json_encode($application));
        } catch (Throwable $e) {
            return new ResponseModel(500, $e->getMessage());
        }

    }//end find()


    /**
     * @param  integer $userId
     * @return ResponseModel
     */
    public function findByUserId(int $userId): ResponseModel
    {
        try {
            $user = $this->userRepository->findByOne('userId', $userId);
            if (!$user) {
                return new ResponseModel(404, 'user could not found');
            }

            return new ResponseModel(200, 'OK', $this->applicationRepository->findByUserId($userId));
        } catch (Throwable $e) {
            return new ResponseModel(500, $e->getMessage());
        }

    }//end findByUserId()


    /**
     * @return ResponseModel
     */
    public function fetchAll(): ResponseModel
    {
        try {
            return new ResponseModel(200, 'OK', $this->applicationRepository->fetchAllActive());
        } catch (Throwable $e) {
            return new ResponseModel(500, $e->getMessage());
        }

    }//end fetchAll()


    /**
     * @param  integer $applicationId
     * @param  string  $payload
     * @return ResponseModel
     */
    public function update(int $applicationId, string $payload): ResponseModel
    {
        try {
            $application = $this->applicationRepository->find($applicationId);
            if (!$application) {
                return new ResponseModel(404, 'application not found');
            }

            $errors         = [];
            $theApplication = $this->validate($errors, $payload);
            if (!empty($errors)) {
                $code = array_keys($errors)[0];
                return new ResponseModel($code, $errors[$code]);
            }

            $fromDate = new DateTime($theApplication->fromDate);
            $toDate   = new DateTime($theApplication->toDate);

            if ($application->getFromDate() === $fromDate
                && $application->getToDate() === $toDate
                && $application->getReason() === $theApplication->reason
            ) {
                return new ResponseModel(202, 'no need to update', $application);
            }

            $application->setReason($theApplication->reason);
            $application->setFromDate($fromDate);
            $application->setToDate($toDate);
            $this->applicationRepository->update($application);

            return new ResponseModel(200, 'OK', $this->applicationRepository->find($applicationId));
        } catch (Throwable $e) {
            return new ResponseModel(500, $e->getMessage());
        }//end try

    }//end update()


    /**
     * @param  integer $applicationId
     * @param  string  $status
     * @return ResponseModel
     */
    public function updateStatus(int $applicationId, string $status): ResponseModel
    {
        try {
            $applicationStatus = json_decode($status, false);
            if (!$applicationStatus) {
                return new ResponseModel(404, 'could not validate incoming status');
            }

            if (!$this->applicationValidator->isStatusValid($applicationStatus->status)) {
                return new ResponseModel(400, 'status is not valid');
            }

            $application = $this->applicationRepository->find($applicationId);
            if (!$application) {
                return new ResponseModel(404, 'application could not found');
            }

            // TODO let admin change the status of the application is current application status is not Pending?
            $user = $this->userRepository->findByOne('userId', $application->getUserId());
            if (!$user) {
                return new ResponseModel(400, 'user is not valid');
            }

            if ($application->getStatus() === $applicationStatus->status) {
                return new ResponseModel(202, 'no need to update', $application);
            }

            if ($application->getStatus() === $applicationStatus->status) {
                return new ResponseModel(202, 'no need to update', $application);
            }

            $application->setStatus($applicationStatus->status);
            $this->applicationRepository->update($application);

            $datetime = $application->getCreatedDatetime()->format('Y/m/d');
            $body     = MailTemplate::employeeTemplate(
                $application->getStatus(),
                $datetime
            );

            $this->mailService->send(
                $user->getEmail(),
                'Your application is updated',
                $body
            );

            return new ResponseModel(
                200,
                'OK',
                $this->applicationRepository->find($application->getApplicationId())
            );
        } catch (Throwable $e) {
            return new ResponseModel(500, $e->getMessage());
        }//end try

    }//end updateStatus()


    /**
     * @param  integer $applicationId
     * @return ResponseModel
     */
    public function delete(int $applicationId): ResponseModel
    {
        try {
            $application = $this->applicationRepository->find($applicationId);
            if (!$application) {
                return new ResponseModel(404, 'application could not found');
            }

            $this->applicationRepository->delete($application);

            return new ResponseModel(204, 'OK');
        } catch (Throwable $e) {
            return new ResponseModel(500, $e->getMessage());
        }

    }//end delete()


    /**
     * @param  array  $errors
     * @param  string $application
     * @return object|null
     */
    private function validate(array &$errors, string $application): ?object
    {
        $theApplication = json_decode($application, false);
        if (!$theApplication) {
            $errors[400] = 'could not validate incoming application object';
            return null;
        }

        if (!$this->applicationValidator->isEndAfterStart($theApplication->fromDate, $theApplication->toDate)) {
            $errors[400] = 'toDate cannot be before fromDate';
            return null;
        }

        if (!$this->applicationValidator->isStartAfterNow($theApplication->fromDate)) {
            $errors[400] = 'fromDate have to be one day from today';
            return null;
        }

        if (!$this->applicationValidator->isDatesNotInRange(
            $theApplication->userId,
            $theApplication->fromDate,
            $theApplication->toDate
        )
        ) {
            $errors[400] = 'fromDate and toDate must not overlap other application dates';
            return null;
        }

        if (!$this->applicationValidator->isReasonNotEmpty($theApplication->reason)) {
            return new ResponseModel(400, 'reason can not be empty');
        }

        return $theApplication;

    }//end validate()


}//end class
