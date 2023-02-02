<?php

namespace Up\Core\Domain\Entities;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
 *  Application entity
 *
 * @Entity
 * @Table(name="application")
 */
class Application implements JsonSerializable
{
    /**
     * Primary key and application identifier
     *
     * @Id
     * @GeneratedValue
     * @Column(type="integer",name="applicationId")
     *
     * @var int
     */
    private int $applicationId;

    /**
     * @Column(type="integer",name="userId")
     *
     * @var int
     */
    private int $userId;

    /**
     * @Column(type="string", name="status", columnDefinition="ENUM('accepted','rejected','pending')")
     *
     * @var string
     */
    private string $status;

    /**
     * @Column(type="datetime",name="fromDate")
     *
     * @var DateTime
     */
    private DateTime $fromDate;

    /**
     * @Column(type="datetime",name="toDate")
     *
     * @var DateTime
     */
    private DateTime $toDate;

    /**
     * @Column(type="string",name="reason")
     *
     * @var string
     */
    private string $reason;

    /**
     * @Column(type="datetime",name="createdDatetime")
     * @Gedmo\Timestampable(on="create")
     *
     * @var DateTime
     */
    private DateTime $createdDatetime;

    /**
     * @Column(type="datetime",name="modifiedDatetime")
     * @Gedmo\Timestampable(on="update")
     *
     * @var DateTime
     */
    private DateTime $modifiedDatetime;

    /**
     * Application to user mapping
     *
     * @ManyToOne(targetEntity="Up\Core\Domain\Entities\User", inversedBy="users", cascade={"merge"})
     * @JoinColumn(name="userId", referencedColumnName="userId")
     */
    private User $user;

    /**
     * Applications logActions mapping
     *
     * @var Collection<int, LogAction>
     * @OneToMany(targetEntity="LogAction", mappedBy="application")
     */
    private Collection $logActions;

    public function __construct()
    {
        $this->logActions = new ArrayCollection();
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
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return DateTime
     */
    public function getFromDate(): DateTime
    {
        return $this->fromDate;
    }

    /**
     * @param DateTime $fromDate
     */
    public function setFromDate(DateTime $fromDate): void
    {
        $this->fromDate = $fromDate;
    }

    /**
     * @return DateTime
     */
    public function getToDate(): DateTime
    {
        return $this->toDate;
    }

    /**
     * @param DateTime $toDate
     */
    public function setToDate(DateTime $toDate): void
    {
        $this->toDate = $toDate;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     */
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * @return DateTime
     */
    public function getCreatedDatetime(): DateTime
    {
        return $this->createdDatetime;
    }

    /**
     * @return DateTime
     */
    public function getModifiedDatetime(): DateTime
    {
        return $this->modifiedDatetime;
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
     * @return Collection
     */
    public function getLogActions(): Collection
    {
        return $this->logActions;
    }

    /**
     * @param Collection $logActions
     */
    public function setLogActions(Collection $logActions): void
    {
        $this->logActions = $logActions;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'applicationId' => $this->getApplicationId(),
            'userId' => $this->getUserId(),
            'status' => $this->getStatus(),
            'from' => $this->getFromDate()->format("d-m-Y H:i"),
            'to' => $this->getToDate()->format("d-m-Y H:i"),
            'reason' => $this->getReason(),
            'createdDatetime' => $this->getCreatedDatetime()->format("d-m-Y H:i"),
            'modifiedDatetime' => $this->getModifiedDatetime()->format("d-m-Y H:i")
        ];
    }
}
