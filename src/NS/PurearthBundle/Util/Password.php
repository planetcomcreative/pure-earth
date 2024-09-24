<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 30/08/16
 * Time: 10:53 AM
 */

namespace NS\PurearthBundle\Util;


class Password
{
    // http://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425
    public static function generate($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }
}