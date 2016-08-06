<?php

namespace OC\UserBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class User
 * @package OC\UserBundle\Entity
 * @ORM\Table(name="nalo_user")
 * @ORM\Entity(repositoryClass="OC\UserBundle\Repository\UserRepository")
 * @UniqueEntity("email", message="user.email.not_unique")
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
     * @Assert\NotBlank(message="user.firstName.not_blank", groups={"registration", "profile_edit"})
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, name="last_name")
     * @Assert\NotBlank(message="user.lastName.not_blank", groups={"registration", "profile_edit"})
     */
    protected $lastName;

    /**
     * @Assert\Email(message="user.email.not_valid", groups={"registration", "profile_edit"})
     * @Assert\NotBlank(message="user.email.not_blank", groups={"registration", "profile_edit"})
     */
    protected $email;

    /**
     * @Assert\NotBlank(message="user.plainPassword.not_blank", groups={"registration"})
     * @Assert\Regex(pattern="/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-;]).{6,}$/", match=true, message="user.plainPassword.not_valid", groups={"registration", "profile_edit"})
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
        $this->rolePro = $rolePro;

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
}
