<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 02/05/17
 * Time: 3:31 PM
 */

namespace NS\Purearth\Sale;


interface SaleVoterInterface
{
    public function supports($object);

    public function applyDiscount($object);

    public function getFinal($object);
}