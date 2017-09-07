<?php

namespace Authorizationzf3\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="user_role_linker", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class UserRoleLinker
{
    /**
     * @var \Shop\Entity\User
     * @ORM\ManyToOne(targetEntity="Shop\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $userid;

    /**
     * @var \Shop\Entity\UserRole
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="Shop\Entity\UserRole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $roleid;


    /**
     * Set userid
     *
     * @param \Shop\Entity\User $userid
     *
     * @return UserRoleLinker
     */
    public function setUserid(\Shop\Entity\User $userid = null)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return \Shop\Entity\User
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set roleid
     *
     * @param \Shop\Entity\UserRole $roleid
     *
     * @return UserRoleLinker
     */
    public function setRoleid(\Shop\Entity\UserRole $roleid = null)
    {
        $this->roleid = $roleid;

        return $this;
    }

    /**
     * Get roleid
     *
     * @return \Shop\Entity\UserRole
     */
    public function getRoleid()
    {
        return $this->roleid;
    }
}
