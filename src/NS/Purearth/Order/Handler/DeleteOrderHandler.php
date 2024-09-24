<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 12/07/16
 * Time: 2:55 PM
 */

namespace NS\Purearth\Order\Handler;


use NS\Purearth\AbstractCommandHandler;
use NS\Purearth\Order\Command\DeleteOrderCommand;
use NS\Purearth\Order\OrderStatus;
use Doctrine\ORM\EntityManagerInterface;

class DeleteOrderHandler extends AbstractCommandHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(DeleteOrderCommand $command)
    {
        $order = $command->getOrder();

        if($order->getStatus() == OrderStatus::OPEN) //Last minute sanity-check, but a processed order should never arrive here
        {
            $this->entityManager->remove($order);
            $this->entityManager->flush();
        }
    }

}