<?php

namespace NS\Purearth\Product\Service;

use NS\Purearth\Product\Exceptions\ProductNotFoundException;

class CourseService
{
    protected $entityMgr;

    public function __construct($entityMgr)
    {
        $this->entityMgr = $entityMgr;
    }

    public function getCurrent($limit = 0)
    {
        $courses = $this->entityMgr->getRepository('NS\Purearth\Product\Course')->findCurrent($limit);
        if (!$courses || empty($courses))
        {
            throw new ProductNotFoundException();
        }

        return $courses;
    }

    public function find($id)
    {
        $course = $this->entityMgr->getRepository('NS\Purearth\Product\Course')->find($id);
        if (!$course || empty($course))
        {
            throw new ProductNotFoundException();
        }

        return $course;
    }
}