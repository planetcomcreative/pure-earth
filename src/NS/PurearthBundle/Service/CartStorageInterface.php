<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 04/07/16
 * Time: 3:16 PM
 */

namespace NS\PurearthBundle\Service;


interface CartStorageInterface
{
    public function initialize($namespace,$lifetime);

    public function get($id);

    public function save($id, $object);

    public function delete($id);
}