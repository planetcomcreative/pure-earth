<?php
namespace NS\Purearth\User\Command;

use Symfony\Component\Validator\Constraints as Assert;

class GenerateUserResetTokenCommand
{
    /**
     * @var string
     */
    private $id;

    /**
     * UpdateUserCommand constructor.
     *
     * @param int $id
     * @param string $password
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }
}
