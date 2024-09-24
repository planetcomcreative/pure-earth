<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 11/07/16
 * Time: 12:09 PM
 */

namespace NS\Purearth\Order;


interface CartInterface
{
    public function __construct();

    public function addItem($item);

    public function removeItem($item);

    public function getItems();
}