<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 04/07/16
 * Time: 3:37 PM
 */

namespace NS\PurearthBundle\Service;


class InMemoryCacheStorage implements CartStorageInterface
{
    private $lifetime;
    private $data = [];

    public function initialize($namespace, $lifetime)
    {
        //no need to handle namespaces
        $this->lifetime = $lifetime;
    }

    public function get($id)
    {
        return (isset($this->data[$id])) ? $this->data[$id]:null;
    }

    public function save($id, $object)
    {
        $this->data[$id] = $object;
    }

    public function delete($id)
    {
        if(isset($this->data[$id])) {
            unset($this->data[$id]);
        }
    }
}