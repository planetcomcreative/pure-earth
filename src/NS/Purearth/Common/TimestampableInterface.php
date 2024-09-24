<?php

namespace NS\Purearth\Common;

interface TimestampableInterface
{

    public function getCreatedAt();
    public function setCreatedAt();
    public function getUpdatedAt();
    public function setUpdatedAt();
}