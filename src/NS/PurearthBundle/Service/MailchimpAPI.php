<?php

namespace NS\PurearthBundle\Service;

use DrewM\MailChimp\MailChimp;
use NS\MemberAdminBundle\Entity\Member;
use NS\Purearth\User\User;

class MailchimpAPI
{
    protected $apiKey;
    protected $primaryListId;

    /**
     * @var MailChimp $mailchimp
     */
    protected $mailchimp;
    protected $create_uri;
    protected $read_uri;
    protected $segment_create_uri;

    public function __construct($apiKey, $primaryListId)
    {
        $this->apiKey = $apiKey;
        $this->primaryListId = $primaryListId;
        $this->create_uri = sprintf("/lists/%s/members", $this->getList());
        $this->read_uri   = sprintf("/lists/%s/members/", $this->getList());
        $this->segment_create_uri = sprintf("/lists/%s/segments", $this->getList());

        $this->initMailchimp($apiKey);
    }

    protected function initMailchimp($apiKey)
    {
        $this->mailchimp = new MailChimp($apiKey);
    }

    public function getAPI()
    {
        return $this->mailchimp;
    }

    public function getList()
    {
        return $this->primaryListId;
    }

    public function getSubscriberHash($email)
    {
        return $this->mailchimp->subscriberHash($email);
    }

    public function quickGetSubscriber($email, $hash = false)
    {
        $hash = $hash ? $hash : $this->getSubscriberHash($email);

        $subscriber = $this->mailchimp->get($this->read_uri.$hash);

        return is_numeric($subscriber['status']) ? false : $subscriber; //Invalid user hash returns http response code
    }

    public function quickUpdateSubscriber($hash, $params)
    {
        return $this->mailchimp->patch($this->read_uri.$hash, $params);
    }

    public function quickDeleteSubscriber($hash)
    {
        return $this->mailchimp->delete($this->read_uri.$hash);
    }

    public function quickAddOrUpdateSubscriber($email, $hash = false, User $user, $forceResubscribe = null)
    {
        $type = 'NONE';

        $params = array(
            'email_address' => $user->getEmail(),
            'merge_fields' => array(
                'FNAME' => $user->getFirstName(),
                'LNAME' => $user->getLastName(),
            )
        );

        /* Is there a subscriber with this id or email? */
        $existing = $this->quickGetSubscriber($email, $hash);

        if($existing && $existing['email_address'] != $email)
        {
            /* Subscriber exists but has changed email address
             * Unsubscribe the existing email address
             */
            $this->quickUpdateSubscriber($hash, array('status'=>'unsubscribed'));
        }

        //Don't re-subscribe someone who has un-subscribed unless they have explicitly requested it
//        if(isset($forceResubscribe))
//        {
            $params['status'] = $forceResubscribe ? 'subscribed':'unsubscribed';
//        }
//        else
//        {
//            $params['status'] = $existing ? $existing['status'] : 'subscribed';
//        }

        return $this->mailchimp->put($this->read_uri.'/'.$this->mailchimp->subscriberHash($email), $params);
    }

    public function quickAddSegment($name, $addresses)
    {
        $params = array(
            'name' => $name,
            'static_segment' => $addresses
        );

        $result = $this->mailchimp->post($this->segment_create_uri, $params, 90);

        return $result;
    }
}
