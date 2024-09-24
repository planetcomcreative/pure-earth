<?php
namespace NS\Purearth\User\Command;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserPasswordCommand
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     * @Assert\Length(min=6,max=64)
     */
    private $password;

    /**
     * UpdateUserCommand constructor.
     *
     * @param int    $id
     * @param string $password
     */
    public function __construct($id, $password)
    {
        $this->id = $id;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function password()
    {
        return $this->password;
    }
}

