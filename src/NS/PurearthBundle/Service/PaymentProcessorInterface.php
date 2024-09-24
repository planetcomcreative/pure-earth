<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 14/07/16
 * Time: 2:11 PM
 */

namespace NS\PurearthBundle\Service;


use NS\Purearth\Order\Charge;

/**
 * Interface PaymentProcessorInterface
 * @package NS\PurearthBundle\Service
 */
interface PaymentProcessorInterface
{
    /**
     * @param $amount
     * @param $currency
     * @param $description
     * @param $email
     * @param $metadata
     * @return Charge
     */
    public function charge($amount, $currency, $description, $email, $metadata);
}