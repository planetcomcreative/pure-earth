<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 01/06/16
 * Time: 4:08 PM.
 */
namespace NS\Purearth\User\Command;

use Symfony\Component\Validator\Constraints as Assert;
use NS\PurearthBundle\Validator\UniqueUser;


/**
 * Class RegisterUserCommand
 * @package NS\Purearth\User\Command
 */
class RegisterUserCommand
{
    /**
     * @Assert\NotBlank
     * @var string
     */
    private $lastName;

    /**
     * @Assert\NotBlank
     * @var string
     */
    private $firstName;

    /**
     * @Assert\NotBlank
     * @Assert\Email(checkMX=true)
     * @UniqueUser
     * @var string
     */
    private $email;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min="6",max="32")
     *
     * @var string
     */
    private $password;

    private $addrStreet,
        $addrCity,
        $addrProv,
        $addrPostal,
        $primaryPhone,
        $secondaryPhone;

    /**
     * UpdateUserCommand constructor.
     * @param int $id
     * @param string $lastName
     * @param string $firstName
     * @param string $email
     * @param $addrStreet
     * @param $addrCity
     * @param $addrProv
     * @param $addrPostal
     * @param $primaryPhone
     * @param $secondaryPhone
     */
    public function __construct($lastName, $firstName, $email, $password, $addrStreet = null, $addrCity = null, $addrPostal = null, $primaryPhone = null, $secondaryPhone = null)
    {
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->email = $email;
        $this->addrStreet = $addrStreet;
        $this->addrCity = $addrCity;
        $this->addrPostal = $addrPostal;
        $this->primaryPhone = $primaryPhone;
        $this->secondaryPhone = $secondaryPhone;
        $this->password = $password;
    }


    public function getAllData()
    {
        return array(
            'lastName' => $this->lastName,
            'firstName' => $this->firstName,
            'email' => $this->email,
            'addrStreet' => $this->addrStreet,
            'addrCity' => $this->addrCity,
            'addrProv' => $this->addrProv,
            'addrPostal' => $this->addrPostal,
            'primaryPhone' => $this->primaryPhone,
            'secondaryPhone' => $this->secondaryPhone,
        );
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getAddrStreet()
    {
        return $this->addrStreet;
    }

    /**
     * @return mixed
     */
    public function getAddrCity()
    {
        return $this->addrCity;
    }

    /**
     * @return mixed
     */
    public function getAddrProv()
    {
        return $this->addrProv;
    }

    /**
     * @return mixed
     */
    public function getAddrPostal()
    {
        return $this->addrPostal;
    }

    /**
     * @return mixed
     */
    public function getPrimaryPhone()
    {
        return $this->primaryPhone;
    }

    /**
     * @return mixed
     */
    public function getSecondaryPhone()
    {
        return $this->secondaryPhone;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
