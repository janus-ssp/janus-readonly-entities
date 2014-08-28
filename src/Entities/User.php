<?php

namespace Janus\Component\ReadonlyEntities\Entities;

use DateTime;
use Exception;

use Doctrine\ORM\Mapping AS ORM;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

use Janus\Component\ReadonlyEntities\Value\Ip;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *  name="user"
 * )
 */
class User implements UserInterface, EquatableInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="uid", type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="userid", type="text", nullable=true)
     */
    protected $username;

    /**
     * A collection of user type names (admin, technical etc.)
     *
     * @var array
     *
     * @ORM\Column(name="type", type="janusUserType")
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="email", length=320, nullable=true)
     */
    protected $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="janusBoolean", nullable=true, options={"default" = "yes"})
     */
    protected $isActive = true;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="`update`", type="janusDateTime", nullable=true)
     */
    protected $updatedAtDate;

    /**
     * @var DateTime
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
     * @var string
     *
     * @ORM\Column(name="data", type="text", nullable=true)
     */
    protected $data;

    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="text", nullable=true)
     */
    protected $secret;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Janus\Component\ReadonlyEntities\Entity\User\Data", mappedBy="user", fetch="LAZY")
     *
     * @todo find out what the difference between user.data column and userData table is
     */
    protected $dataCollection;

    /**
     * @param $username
     * @param array $type
     * @param string|null $email
     * @param bool $isActive
     */
    public function __construct(
        $username,
        array $type,
        $email = null,
        $isActive = true
    )
    {
        $this->setUsername($username);
        $this->type = $type;
        $this->setEmail($email);
        $this->activate($isActive);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive === true;
    }

    /**
     * @param bool $active
     * @return $this
     */
    protected function activate($active = true)
    {
        $this->isActive = ($active === true);
    }

    /**
     * @param string $username
     * @return $this
     * @throws Exception
     */
    protected function setUsername($username)
    {
        if (empty($username)) {
            throw new Exception("Invalid username '{$username}'");
        }

        $this->username = $username;

        return $this;
    }

    /**
     * @param string $email
     */
    protected function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->secret;
    }

    /**
     * @inheritDoc
     *
     * Note that Janus does not use salted passwords (yet)
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    }

    /**
     * @inheritDoc
     */
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->getPassword() !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->getUsername() !== $user->getUsername()) {
            return false;
        }

        return true;
    }
}