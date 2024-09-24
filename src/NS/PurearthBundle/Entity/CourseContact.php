<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 24/08/16
 * Time: 12:39 PM
 */

namespace NS\PurearthBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

class CourseContact
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    protected $text;

    /**
     * @var string
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    protected $email;

    /**
     * @Recaptcha\IsTrue
     */
    protected $recaptcha;

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getRecaptcha()
    {
        return $this->recaptcha;
    }

    /**
     * @param mixed $recaptcha
     */
    public function setRecaptcha($recaptcha)
    {
        $this->recaptcha = $recaptcha;
    }
}