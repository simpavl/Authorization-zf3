<?php

namespace Authorizationzf3\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="email", columns={"email"})})
 * @ORM\Entity
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="text", length=65535, precision=0, scale=0, nullable=false, unique=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="text", length=65535, precision=0, scale=0, nullable=false, unique=false)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, precision=0, scale=0, nullable=false, unique=false)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="useractive", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $useractive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthdate", type="date", precision=0, scale=0, nullable=true, unique=false)
     */
    private $birthdate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="gender", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, precision=0, scale=0, nullable=false, unique=false)
     */
    private $password;

	/**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Shop\Entity\UserRole")
     * @ORM\JoinTable(name="user_role_linker",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $roles;
    /**
     * Get id
     *
     * @return integer
     */

	 /**
     * Initializes the roles variable.
     */

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $role;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

	/**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set useractive
     *
     * @param boolean $useractive
     *
     * @return User
     */
    public function setUseractive($useractive)
    {
        $this->useractive = $useractive;

        return $this;
    }

    /**
     * Get useractive
     *
     * @return boolean
     */
    public function getUseractive()
    {
        return $this->useractive;
    }

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return User
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set gender
     *
     * @param boolean $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return boolean
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

	/**
     * Get role.
     *
     * @return array
     */
    public function getRoles()
    {
        return $this->roles->getValues();
    }

    /**
     * Add a role to the user.
     *
     * @param Role $role
     *
     * @return void
     */
    public function addRole($role)
    {
        $this->roles[] = $role;
    }

	public function getStatusAsString()
    {
        if($this->getUseractive() == 1)
        {
            return 'Active';
        }else{
            return 'Retired';
        }
    }
	public function getGenderAsString()
    {
        if($this->getGender() == 1)
        {
            return 'Male';
        }else{
            return 'Female';
        }
    }
    public function getFullName()
    {
        return $this->getFirstname() . ' ' . $this->getSurname();
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }
}
