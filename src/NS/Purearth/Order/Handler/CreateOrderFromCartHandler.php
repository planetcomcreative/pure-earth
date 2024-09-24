<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 11/07/16
 * Time: 12:19 PM
 */

namespace NS\Purearth\Order\Handler;


use Doctrine\ORM\EntityManagerInterface;
use NS\Purearth\AbstractCommandHandler;
use NS\Purearth\Order\Command\CreateOrderFromCartCommand;
use NS\Purearth\Order\OrderProduct;
use NS\Purearth\Order\Service\OrderService;
use NS\Purearth\Product\AbstractProduct;
use NS\Purearth\Product\ProductReferenceFactory;
use NS\Purearth\User\Exceptions\UserNotFoundException;
use NS\Purearth\Order\Order;
use NS\PurearthBundle\Service\SaleService;

class CreateOrderFromCartHandler extends AbstractCommandHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ProductReferenceFactory
     */
    private $factory;

    /**
     * @var OrderService
     */
    private $orderService;

    /**
     * @var SaleService
     */
    private $saleService;
    
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setSaleService(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function setProductReferenceFactory(ProductReferenceFactory $factory)
    {
        $this->factory = $factory;
    }

    public function setOrderService(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function __invoke(CreateOrderFromCartCommand $command)
    {
        /**
         * TODO: Order needs to persist sale price somewhere
         */
        $user = $this->entityManager->getReference('NS\Purearth\User\User', $command->getUser()->getId());
        $order = new Order($user);

        $refs = $this->factory->getReferences(array_keys($command->getCart()->getItems()));

        /**
         * @var AbstractProduct $item
         */
        foreach($refs as $ref)
        {
            $this->saleService->applyDiscounts($ref);
        }

        $order->populateFromCart($command->getCart(), $refs);

        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }
}