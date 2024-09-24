<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 29/08/16
 * Time: 6:28 PM
 */

namespace NS\Purearth\User\Command;


class ConfirmUserCommand
{
    /**
     * @var string
     */
    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }
}