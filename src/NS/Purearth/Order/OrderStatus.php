<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 11/07/16
 * Time: 12:39 PM
 */

namespace NS\Purearth\Order;


class OrderStatus
{
    const OPEN             = 0,
          PAID             = 1,
          PAYMENT_DECLINED = 2,
          CANCELLED        = 3,
          REFUNDED         = 4,
          PAYMENT_FAILED   = 5;

    public static function getValues()
    {
        return array(
            self::OPEN => 'Open',
            self::PAID => 'Paid',
            self::PAYMENT_DECLINED => 'Declined',
            self::CANCELLED => 'Cancelled',
            self::REFUNDED => 'Refunded',
            self::PAYMENT_FAILED => 'Failed'
        );
    }

    public static function getValuesForSelect()
    {
        return array(
            'Open' => self::OPEN,
            'Paid' => self::PAID,
            'Declined' => self::PAYMENT_DECLINED,
            'Cancelled' => self::CANCELLED,
            'Refunded' => self::REFUNDED,
            'Failed' => self::PAYMENT_FAILED
        );
    }

    public static function getValue($value)
    {
        $values = self::getValues();

        return $values[$value];
    }

}