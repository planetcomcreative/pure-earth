<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 09/08/16
 * Time: 4:23 PM
 */

namespace NS\Purearth\Order\Handler;


use Doctrine\ORM\EntityManagerInterface;
use NS\Purearth\Order\Command\CourseRegistrationCommand;
use NS\Purearth\Order\CourseRegistration;
use NS\Purearth\Product\Exceptions\CourseUnavailableException;

class CourseRegistrationHandler
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(CourseRegistrationCommand $command)
    {

        $regData = $command->getRegData();
        $order   = $command->getOrder();
        $user    = $this->entityManager->getReference('NS\Purearth\User\User', $command->getUser()->getId());
        $course  = $this->entityManager->getRepository('NS\Purearth\Product\Course')->find($regData['id']);
        $timest = strtotime('23:59:59 Today');
        $date = new \DateTime('@'.$timest);

        if($course->getSeatsRemaining() < count($regData['newRegistrations']) && $course->getRegistrationCutoff() >= $date)
        {
            throw new CourseUnavailableException('This course no longer has sufficient available seats');
        }

        foreach($regData['newRegistrations'] as $registration)
        {
            $courseRegistration = new CourseRegistration($course);
            $courseRegistration->setOrder($order);
            $courseRegistration->setUser($user);
            $courseRegistration->setRegistrantInfo($registration['name']."\n".$registration['address']."\n".$registration['postalCode']."\n".$registration['phone']);

            $course->addCourseRegistration($courseRegistration);
        }

        $this->entityManager->getConnection()->beginTransaction();

        $this->entityManager->persist($course);
        $this->entityManager->flush();
        $this->entityManager->refresh($course);

        if($course->getSeatsRemaining() >= 0)
        {
            $this->entityManager->getConnection()->commit();
        }
        else
        {
            $this->entityManager->getConnection()->rollback();

            throw new CourseUnavailableException('This course no longer has sufficient available seats');
        }
    }
}