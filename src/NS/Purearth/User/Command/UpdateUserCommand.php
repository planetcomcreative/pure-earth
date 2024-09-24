<?php
namespace NS\Purearth\User\Command;

use NS\Purearth\User\User;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserCommand
{
    protected $id;

    /**
     * @Assert\NotBlank
     * @var string
     */
    protected $lastName;

    /**
     * @Assert\NotBlank
     * @var string
     */
    protected $firstName;

    /**
     * @Assert\NotBlank
     * @Assert\Email()
     * @var string
     */
    protected $email;

    protected $addrStreet,
                $addrCity,
                $addrProv,
                $addrPostal,
                $primaryPhone,
                $secondaryPhone;
    /**
     * @var null
     */
    protected $forceResubscribe;

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
    public function __construct($id, $lastName, $firstName, $email, $addrStreet = null, $addrCity = null, $addrProv = null, $addrPostal = null, $primaryPhone = null, $secondaryPhone = null, $forceResubscribe = null)
    {
        $this->id = $id;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->email = $email;
        $this->addrStreet = $addrStreet;
        $this->addrCity = $addrCity;
        $this->addrProv = $addrProv;
        $this->addrPostal = $addrPostal;
        $this->primaryPhone = $primaryPhone;
        $this->secondaryPhone = $secondaryPhone;
        $this->forceResubscribe = $forceResubscribe;
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
            'forceResubscribe' => $this->forceResubscribe
        );
    }

    public function getId()
    {
        return $this->id;
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
     * @return null
     */
    public function getForceResubscribe()
    {
        return $this->forceResubscribe;
    }


}
