<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 11/07/16
 * Time: 4:06 PM
 */

namespace NS\Purearth\Product;


use Doctrine\ORM\EntityManagerInterface;

class ProductReferenceFactory
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * ProductReferenceFactory constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $ids
     */
    public function getReferences($ids)
    {
        $refs = array();
        foreach($ids as $id)
        {
            $refs[$id] = $this->entityManager->getReference('NS\Purearth\Product\AbstractProduct', $id);
        }

        return $refs;
    }
}