<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 14/07/16
 * Time: 2:08 PM
 */

namespace NS\Purearth\Order\Exceptions;


/**
 * Class PaymentDeclinedException
 * @package NS\Purearth\Order\Exceptions
 */
class PaymentDeclinedException extends \Exception
{
    /**
     * @var
     */
    protected $declineData;

    /**
     * @param array $data
     */
    public function setDeclineData(array $data)
    {
        $this->declineData = $data;
    }

    /**
     * @return mixed
     */
    public function getDeclineData()
    {
        return $this->declineData;
    }
}