<?php

namespace Up\Core\Domain\Entities;

use DateTime;
use JsonSerializable;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 *  LogAction entity
 *
 * @Entity
 * @Table(name="logAction")
 */
class LogAction implements JsonSerializable
{
    /**
     * Primary key and logAction identifier
     *
     * @Id
     * @GeneratedValue
     * @Column(type="integer",name="logActionId")
     *
     * @var int
     */
    private int $logActionId;

    /**
     * @Column(type="integer",name="userId")
     *
     * @var int
     */
    private int $userId;

    /**
     * @Column(type="integer",name="applicationId")
     *
     * @var int
     */
    private int $applicationId;

    /**
     * @Column(type="string", name="applicationStatus", columnDefinition="ENUM('accepted','rejected','pending')")
     *
     * @var string
     */
    private string $applicationStatus;

    /**
     * @Column(type="datetime",name="createdDatetime")
     * @Gedmo\Timestampable(on="create")
     *
     * @var DateTime
     */
    private DateTime $createdDatetime;

    /**
     * Many features have one product. This is the owning side.
     *
     * @ManyToOne(targetEntity="User", inversedBy="logActions")
     * @JoinColumn(name="userId", referencedColumnName="userId")
     */
    private User $user;

    /**
     * Many features have one product. This is the owning side.
     *
     * @ManyToOne(targetEntity="Application", inversedBy="logActions")
     * @JoinColumn(name="applicationId", referencedColumnName="applicationId")
     */
    private Application $application;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getLogActionId(): int
    {
        return $this->logActionId;
    }

    /**
     * @param int $logActionId
     */
    public function setLogActionId(int $logActionId): void
    {
        $this->logActionId = $logActionId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getApplicationId(): int
    {
        return $this->applicationId;
    }

    /**
     * @param int $applicationId
     */
    public function setApplicationId(int $applicationId): void
    {
        $this->applicationId = $applicationId;
    }

    /**
     * @return string
     */
    public function getApplicationStatus(): string
    {
        return $this->applicationStatus;
    }

    /**
     * @param string $applicationStatus
     */
    public function setApplicationStatus(string $applicationStatus): void
    {
        $this->applicationStatus = $applicationStatus;
    }

    /**
     * @return DateTime
     */
    public function getCreatedDatetime(): DateTime
    {
        return $this->createdDatetime;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return Application
     */
    public function getApplication(): Application
    {
        return $this->application;
    }

    /**
     * @param Application $application
     */
    public function setApplication(Application $application): void
    {
        $this->application = $application;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'logActionId' => $this->getApplicationId(),
            'userId' => $this->getUserId(),
            'applicationId' => $this->getApplicationId(),
            'applicationStatus' => $this->getApplicationStatus(),
            'createdDatetime' => $this->getCreatedDatetime()->format("d-m-Y H:i")
        ];
    }
}
