<?php

namespace UserBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package UserBundle\Entity
 * @ORM\Table(name="nalo_user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    const ROLE_USER_NAME = "Amateur";
    const ROLE_PRO_NAME = "Naturaliste";

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, name="first_name")
     * @Assert\NotBlank(message="user.firstName.not_blank", groups={"Registration", "Profile"})
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, name="last_name")
     * @Assert\NotBlank(message="user.lastName.not_blank", groups={"Registration", "Profile"})
     */
    protected $lastName;

    /**
     * @Assert\Regex(pattern="/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/", match=true, message="user.plainPassword.not_valid", groups={"Registration", "Profile", "Resetting"})
     */
    protected $plainPassword;

    /**
     * @ORM\Column(type="datetime", nullable=false, name="create_at")
     */
    protected $createAt;

    protected $rolePro;

    public function __construct()
    {
        parent::__construct();

        $this->createAt = new \DateTime();
        $this->roles = array('ROLE_USER');
        $this->enabled = true;
        $this->rolePro = false;
    }

    public function setEmail($email){
        $this->email = $email;
        $this->username = $email;
	    return $this;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return User
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set key
     *
     * @param string $key
     *
     * @return User
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param bool $rolePro
     */
    public function setRolePro($rolePro)
    {
        if(true === $rolePro){
        	$this->roles = array('ROLE_PRO');
        }

        return $this;
    }

    /**
     * @param bool $rolePro
     */
    public function getRolePro()
    {
        return $this->rolePro;
    }

    public function getFullNameRole(){
        if($this->hasRole('ROLE_PRO')){
            return self::ROLE_PRO_NAME;
        }

        return self::ROLE_USER_NAME;
    }

    public function getFullName()
    {
    	return $this->firstName.' '.$this->lastName;
    }
}
