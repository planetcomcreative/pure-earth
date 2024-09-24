<?php
declare(strict_types = 1);

namespace NS\Purearth\User\Handler;

use Doctrine\ORM\EntityManagerInterface;
use NS\Purearth\AbstractCommandHandler;
use NS\Purearth\User\Command\PurgeUsersCommand;
use NS\Purearth\User\User;

class PurgeUsersHandler extends AbstractCommandHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityMgr
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(PurgeUsersCommand $command)
    {
        $conn = $this->entityManager->getConnection();
        $date = new \DateTime();
        $date->sub(new \DateInterval('P2D'));
        $sql = 'DELETE u FROM users AS u LEFT JOIN orders AS o ON o.user_id = u.id WHERE u.confirmed != true AND o.id IS NULL AND u.updated_at < :date';

        $stmt = $conn->prepare($sql);
        $stmt->executeQuery(['date'=>$date->format('Y-m-d H:i:s')]);
    }
}
