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
 *  User entity
 *
 * @Entity
 * @Table(name="user")
 */
class User implements JsonSerializable
{
    /**
     * Primary key and user identifier
     *
     * @Id
     * @GeneratedValue
     * @Column(type="integer",name="userId")
     *
     * @var int
     */
    private int $userId;

    /**
     * @Column(type="string",name="firstName")
     *
     * @var string
     */
    private string $firstName;

    /**
     * @Column(type="string",name="lastName")
     *
     * @var string
     */
    private string $lastName;

    /**
     * @Column(type="string",name="email")
     *
     * @var string
     */
    private string $email;

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
     * @Column(type="string",name="salt")
     *
     * @var string
     */
    private string $salt;

    /**
     * @Column(type="string", name="role", columnDefinition="ENUM('admin','employee')")
     *
     * @var string
     */
    private string $role;

    /**
     * User application mapping
     * @OneToMany(targetEntity="Up\Core\Domain\Entities\Application", mappedBy="user")
     *
     * @var Collection<int, Application>
     */
    private Collection $applications;

    /**
     * User logActions mapping
     * @OneToMany(targetEntity="LogAction", mappedBy="user")
     *
     * @var Collection<int, LogAction>
     */
    private Collection $logActions;

    public function __construct()
    {
        $this->logActions = new ArrayCollection();
        $this->applications = new ArrayCollection();
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
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
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
     * @return string
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt(string $salt): void
    {
        $this->salt = $salt;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return Collection
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    /**
     * @return Collection
     */
    public function getLogActions(): Collection
    {
        return $this->logActions;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'userId' => $this->getUserId(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'email' => $this->getEmail(),
            'role' => $this->getRole(),
            'createdDatetime' => $this->getCreatedDatetime()->format("d-m-Y H:i")
        ];
    }
}
