<?php

namespace NS\Purearth\User;

use NS\Purearth\Common\TimestampableTrait;
use NS\Purearth\Common\TimestampableInterface;
use NS\Purearth\Order\CourseRegistration;
use NS\Purearth\Order\OrderStatus;
use Symfony\Component\Security\Core\User\UserInterface;
use \DateTime;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class AbstractUser
 * @package NS\Purearth\User
 */
class User implements TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $salt;

    /**
     * @var string
     */
    protected $plainPassword;

    /**
     * @var DateTime
     */
    protected $registeredOn;

    /**
     * @var DateTime
     */
    protected $lastLogin;

    /**
     * @var string
     */
    protected $addrStreet;

    /**
     * @var string
     */
    protected $addrCity;

    /**
     * @var string
     */
    protected $addrProv;

    /**
     * @var string
     */
    protected $addrPostal;

    /**
     * @var string
     */
    protected $primaryPhone;

    /**
     * @var string
     */
    protected $secondaryPhone;

    /**
     * @var boolean
     */
    protected $admin;

    /**
     * @var ArrayCollection
     */
    protected $courseRegistrations;

    /**
     * @var boolean
     */
    protected $confirmed;

    /**
     * @var string
     */
    protected $confirmationToken;

    /**
     * @var string
     */
    protected $resetToken;

    /**
     * @var string $mailchimpSubscriberHash
     *
     * This is just an md5 hash of the email address, but we store it directly so that if they change their email,
     * we can find the previous record on mailchimp and unsubscribe it
     */
    private $mailchimpSubscriberHash;

    protected $forceResubscribe;

    /**
     * AbstractUser constructor.
     */
    public function __construct()
    {
        $this->createdAt = $this->updatedAt = new DateTime();
        $this->salt = sha1($this->createdAt->format('U').uniqid());
        $this->admin = false;
        $this->courseRegistrations = new ArrayCollection();

        $this->confirmed = false;
        $this->confirmationToken = sha1($this->salt.uniqid());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName() ? $this->getName() : '';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->firstName.' '.$this->lastName;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return DateTime
     */
    public function getRegisteredOn()
    {
        return $this->registeredOn;
    }

    /**
     * @param DateTime $registeredOn
     */
    public function setRegisteredOn($registeredOn)
    {
        $this->registeredOn = $registeredOn;
    }

    /**
     * @return DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param DateTime $lastLogin
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * @return string
     */
    public function getAddrStreet()
    {
        return $this->addrStreet;
    }

    /**
     * @param string $addrStreet
     */
    public function setAddrStreet($addrStreet)
    {
        $this->addrStreet = $addrStreet;
    }

    /**
     * @return string
     */
    public function getAddrCity()
    {
        return $this->addrCity;
    }

    /**
     * @param string $addrCity
     */
    public function setAddrCity($addrCity)
    {
        $this->addrCity = $addrCity;
    }

    /**
     * @return string
     */
    public function getAddrProv()
    {
        return $this->addrProv;
    }

    /**
     * @param string $addrProv
     */
    public function setAddrProv($addrProv)
    {
        $this->addrProv = $addrProv;
    }

    /**
     * @return string
     */
    public function getAddrPostal()
    {
        return strtoupper($this->addrPostal);
    }

    /**
     * @param string $addrPostal
     */
    public function setAddrPostal($addrPostal)
    {
        $this->addrPostal = $addrPostal;
    }

    /**
     * @return string
     */
    public function getPrimaryPhone()
    {
        return $this->primaryPhone;
    }

    /**
     * @param string $primaryPhone
     */
    public function setPrimaryPhone($primaryPhone)
    {
        $this->primaryPhone = $primaryPhone;
    }

    /**
     * @return string
     */
    public function getSecondaryPhone()
    {
        return $this->secondaryPhone;
    }

    /**
     * @param string $secondaryPhone
     */
    public function setSecondaryPhone($secondaryPhone)
    {
        $this->secondaryPhone = $secondaryPhone;
    }

    /**
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->admin;
    }

    /**
     * @param boolean $admin
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    }

    public function getProfileData()
    {
        $data = array();

        foreach(array('id', 'firstName', 'lastName', 'email', 'addrStreet', 'addrCity', 'addrProv', 'addrPostal', 'primaryPhone', 'secondaryPhone') as $field)
        {
            $data[$field] = $this->$field;
        }

        return $data;
    }

    public function updateProfile($data)
    {
        foreach(array('firstName', 'lastName', 'email', 'addrStreet', 'addrCity', 'addrProv', 'addrPostal', 'primaryPhone', 'secondaryPhone', 'forceResubscribe') as $field)
        {
            $this->$field = $data[$field];
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getCourseRegistrations()
    {
        $out = [];

        /**
         * @var CourseRegistration $cr
         */
        foreach($this->courseRegistrations as $cr)
        {
            if($cr->getOrder() && $cr->getOrder()->getStatus() == OrderStatus::PAID)
            {
                $out[] = $cr;
            }
        }

        return $out;
    }

    /**
     * @return ArrayCollection
     */
    public function getFutureCourseRegistrations()
    {
        $regs = [];
        $timest = strtotime('00:00:00 Today');
        $date = new \DateTime('@'.$timest);
        /**
         * @var CourseRegistration $creg
         */
        foreach($this->getCourseRegistrations() as $creg)
        {
            if($creg->getCourse()->getDate() >= $date)
            {
                $regs[] = $creg;
            }
        }

        return $regs;
    }

    /**
     * @param ArrayCollection $courseRegistrations
     */
    public function setCourseRegistrations($courseRegistrations)
    {
        $this->courseRegistrations = $courseRegistrations;
    }

    /**
     * @return boolean
     */
    public function isConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param boolean $confirmed
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    }

    /**
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * @return string
     */
    public function getResetToken()
    {
        return $this->resetToken;
    }

    /**
     * @param string $resetToken
     */
    public function setResetToken($resetToken)
    {
        $this->resetToken = $resetToken;
    }

    /**
     * @return string
     */
    public function getMailchimpSubscriberHash()
    {
        return $this->mailchimpSubscriberHash;
    }

    /**
     * @param string $mailchimpSubscriberHash
     */
    public function setMailchimpSubscriberHash($mailchimpSubscriberHash)
    {
        $this->mailchimpSubscriberHash = $mailchimpSubscriberHash;
    }

    /**
     * @return mixed
     */
    public function getForceResubscribe()
    {
        return $this->forceResubscribe;
    }

    /**
     * @param mixed $forceResubscribe
     */
    public function setForceResubscribe($forceResubscribe)
    {
        $this->forceResubscribe = $forceResubscribe;
    }
}
