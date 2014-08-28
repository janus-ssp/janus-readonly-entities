<?php

namespace Janus\Component\ReadonlyEntities\Entities\Connection;

use DateTime;

use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation AS Serializer;

use Janus\Component\ReadonlyEntities\Entities\Connection;
use Janus\Component\ReadonlyEntities\Entities\User;
use Janus\Component\ReadonlyEntities\Value\Ip;

/**
 * @ORM\Entity(
 *  repositoryClass="Janus\Component\ReadonlyEntities\Entity\Connection\RevisionRepository"
 * )
 * @ORM\Table(
 *  name="connectionRevision",
 *  uniqueConstraints={@ORM\UniqueConstraint(name="unique_revision",columns={"eid", "revisionid"})}
 * )
 */
class Revision
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @var Connection
     *
     * @ORM\ManyToOne(targetEntity="Janus\Component\ReadonlyEntities\Entity\Connection", inversedBy="revisions")
     * @ORM\JoinColumn(name="eid", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     * @Serializer\Groups({"compare"})
     */
    protected $connection;

    /**
     * @var string
     *
     * @ORM\Column(name="entityid", type="text")
     * @Serializer\Groups({"compare"})
     *
     */
    protected $name;

    /**
     * @var int
     *
     * @ORM\Column(name="revisionid", type="integer")
     */
    protected $revisionNr;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="text", nullable=true)
     * @Serializer\Groups({"compare"})
     */
    protected $state;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="text", nullable=true)
     * @Serializer\Groups({"compare"})
     */
    protected $type;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="expiration", type="janusDateTime", nullable=true)
     */
    protected $expirationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="metadataurl", type="text", nullable=true)
     * @Serializer\Groups({"compare"})
     */
    protected $metadataUrl;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="metadata_valid_until", type="datetime", nullable=true)
     */
    protected $metadataValidUntil;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="metadata_cache_until", type="datetime", nullable=true)
     */
    protected $metadataCacheUntil;

    /**
     * @var bool
     *
     * @ORM\Column(name="allowedall", type="janusBoolean", options={"default" = "yes"})
     * @Serializer\Groups({"compare"})
     */
    protected $allowAllEntities = true;

    /**
     * @var string
     *
     * @ORM\Column(name="arp_attributes", type="array", nullable=true)
     * @Serializer\Groups({"compare"})
     *
     */
    protected $arpAttributes;

    /**
     * @var string
     *
     * @ORM\Column(name="manipulation", type="text", columnDefinition="mediumtext", nullable=true)
     *
     */
    protected $manipulationCode;

    /**
     * @var string
     *
     * @Serializer\Groups({"compare"})
     * @Serializer\Accessor(getter="isManipulationCodePresent")
     */
    protected $manipulationCodePresent;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Janus\Component\ReadonlyEntities\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="uid", nullable=true)
     */
    protected $updatedByUser;

    /**
     * @var Datetime
     *
     * @ORM\Column(name="created", type="janusDateTime", nullable=true)
     */
    protected $createdAtDate;

    /**
     * @var Ip
     *
     * @ORM\Column(name="ip", type="janusIp", nullable=true)
     */
    protected $updatedFromIp;

    /**
     * @var int
     *
     * @ORM\Column(name="parent", type="integer", nullable=true)
     */
    protected $parentRevisionNr;

    /**
     * @var string
     *
     * @ORM\Column(name="revisionnote", type="text", nullable=true)
     */
    protected $revisionNote;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     * @Serializer\Groups({"compare"})
     */
    protected $notes;
    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="janusBoolean", options={"default" = "yes"})
     * @Serializer\Groups({"compare"})
     *
     */
    protected $isActive = true;

    /**
     * @var \Doctrine\ORM\PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="Janus\Component\ReadonlyEntities\Entity\Connection\Revision\Metadata", mappedBy="connectionRevision", fetch="LAZY")
     * @Serializer\Groups({"compare"})
     *
     */
    protected $metadata;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Janus\Component\ReadonlyEntities\Entity\Connection\Revision\AllowedConnectionRelation", mappedBy="connectionRevision", cascade={"persist", "remove"})
     * @Serializer\Groups({"compare"})
     */
    protected $allowedConnectionRelations;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Janus\Component\ReadonlyEntities\Entity\Connection\Revision\BlockedConnectionRelation", mappedBy="connectionRevision", cascade={"persist", "remove"})
     * @Serializer\Groups({"compare"})
     */
    protected $blockedConnectionRelations;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Janus\Component\ReadonlyEntities\Entity\Connection\Revision\DisableConsentRelation", mappedBy="connectionRevision", cascade={"persist", "remove"})
     * @Serializer\Groups({"compare"})
     */
    protected $disableConsentConnectionRelations;

    /**
     * @param Connection  $connection
     * @param int $revisionNr
     * @param int|null $parentRevisionNr
     * @param string $revisionNote
     * @param string $state
     * @param DateTime|null $expirationDate
     * @param string|null $metadataUrl
     * @param bool $allowAllEntities
     * @param string|null| $arpAttributes
     * @param string|null $manipulationCode
     * @param bool $isActive
     * @param string|null| $notes
     */
    public function __construct(
        Connection $connection,
        $revisionNr,
        $parentRevisionNr = null,
        $revisionNote,
        $state,
        \DateTime $expirationDate = null,
        $metadataUrl = null,
        $allowAllEntities,
        $arpAttributes,
        $manipulationCode = null,
        $isActive,
        $notes = null,
        array $allowedConnections = array(),
        array $blockedConnections = array(),
        array $disableConsentConnections = array()
    )
    {
        $this->connection       = $connection;
        $this->name             = $connection->getName();
        $this->type             = $connection->getType();
        $this->revisionNr       = $revisionNr;
        $this->parentRevisionNr = $parentRevisionNr;
        $this->state            = $state;
        $this->expirationDate   = $expirationDate;
        $this->metadataUrl      = $metadataUrl;
        $this->allowAllEntities = $allowAllEntities;
        $this->arpAttributes    = $arpAttributes;
        $this->manipulationCode = $manipulationCode;
        $this->isActive         = $isActive;
        $this->notes            = $notes;

        foreach ($allowedConnections as $allowedConnection) {
            $this->allowConnection($allowedConnection);
        }

        foreach ($blockedConnections as $blockedConnection) {
            $this->blockConnection($blockedConnection);
        }

        foreach ($disableConsentConnections as $disableConsentConnection) {
            $this->disableConsentForConnection($disableConsentConnection);
        }

        $this->setRevisionNote($revisionNote);
    }

    /**
     * @param string $revisionNote
     * @throws \InvalidArgumentException
     */
    protected function setRevisionNote($revisionNote)
    {
        if (!is_string($revisionNote) || empty($revisionNote)) {
            throw new \InvalidArgumentException("Invalid revision note '{$revisionNote}'");
        }
        $this->revisionNote = $revisionNote;
    }

    /**
     * @param \DateTime $createdAtDate
     * @return $this
     */
    protected function setCreatedAtDate(DateTime $createdAtDate)
    {
        $this->createdAtDate = $createdAtDate;
        return $this;
    }

    /**
     * @param User $updatedByUser
     * @return $this
     */
    protected function setUpdatedByUser(User $updatedByUser)
    {
        $this->updatedByUser = $updatedByUser;
        return $this;
    }

    /**
     * @param \Janus\Component\ReadonlyEntities\Value\Ip $updatedFromIp
     * @return $this
     */
    protected function setUpdatedFromIp(Ip $updatedFromIp)
    {
        $this->updatedFromIp = $updatedFromIp;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getRevisionNr()
    {
        return $this->revisionNr;
    }

    /**
     * @return \Doctrine\ORM\PersistentCollection
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    public function isManipulationCodePresent()
    {
        return !empty($this->manipulationCode);
    }

    public function getState()
    {
        return $this->state;
    }

    public function getUpdatedByUser()
    {
        return $this->updatedByUser;
    }

    public function getRevisionNote()
    {
        return $this->revisionNote;
    }

    public function getCreatedAtDate()
    {
        return $this->createdAtDate;
    }

    protected function allowConnection($connection)
    {
        $this->allowedConnectionRelations[] = new Connection\Revision\AllowedConnectionRelation(
            $this,
            $connection
        );
        return $this;
    }

    protected function blockConnection($connection)
    {
        $this->blockedConnectionRelations[] = new Connection\Revision\BlockedConnectionRelation(
            $this,
            $connection
        );
        return $this;
    }

    protected function disableConsentForConnection($connection)
    {
        $this->disableConsentConnectionRelations[] = new Connection\Revision\DisableConsentRelation(
            $this,
            $connection
        );
        return $this;
    }
}
