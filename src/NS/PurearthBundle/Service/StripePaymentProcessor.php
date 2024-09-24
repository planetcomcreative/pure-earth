<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 14/07/16
 * Time: 2:26 PM
 */

namespace NS\PurearthBundle\Service;


use NS\Purearth\Order\Charge;
use NS\Purearth\Order\Exceptions\PaymentApiCommunicationException;
use NS\Purearth\Order\Exceptions\PaymentApiException;
use NS\Purearth\Order\Exceptions\PaymentDeclinedException;
use NS\StripeBundle\Entity\StripeResponse;
use NS\StripeBundle\Service\StripeProcessor;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class StripePaymentProcessor
 * @package NS\PurearthBundle\Service
 */
class StripePaymentProcessor implements PaymentProcessorInterface
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var StripeProcessor
     */
    protected $stripe;

    /**
     * StripePaymentProcessor constructor.
     * @param RequestStack $requestStack
     * @param StripeProcessor $stripe
     */
    public function __construct(RequestStack $requestStack, StripeProcessor $stripe)
    {
        $this->requestStack = $requestStack;
        $this->stripe       = $stripe;
    }

    /**
     * @param $amount
     * @param $currency
     * @param $description
     * @param $email
     * @param array $metadata
     * @return Charge
     */
    public function charge($amount, $currency, $description, $email, $metadata = array())
    {
        $response = json_decode($this->requestStack->getCurrentRequest()->request->get('stripeResponse'));
        $stripeResponse = new StripeResponse();
        $stripeResponse->fromResponse($response);

        return $this->_charge($amount, $currency, $description, $email, $stripeResponse->getToken(), array('metadata'=>$metadata));
    }

    /**
     * @param $amount
     * @param $currency
     * @param $description
     * @param $email
     * @param $token
     * @param $metadata
     * @return \Stripe\Charge
     */
    public function _charge($amount, $currency, $description, $email, $token, $metadata)
    {
        $options = array('metadata'=>$metadata);

        try
        {
            $chargeObj = $this->stripe->charge(floatval($amount), $token, $currency, $description, $email, $options);

            $charge = new Charge();
            $charge->setChargeId($chargeObj->id);
            $charge->setAmount(floatval($amount));
            $charge->setCard($chargeObj->source->brand);
            $charge->setLast4($chargeObj->source->last4);
            $charge->setCardHolder($chargeObj->source->name);
            $charge->setCurrency($chargeObj->currency);

            return $charge;
        }
        catch(\Stripe\Error\Card $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];

            $data = array('status'=>$e->getHttpStatus(), 'type'=>$err['type'], 'code'=>$err['code'], 'message'=>$err['message']);

            $err = new PaymentDeclinedException();
            $err->setDeclineData($data);
            throw $err;
        }
        catch (\Stripe\Error\RateLimit $e)
        {
            throw new PaymentApiCommunicationException($e->getMessage());
        }
        catch (\Stripe\Error\InvalidRequest $e)
        {
            throw new PaymentApiCommunicationException($e->getMessage());
        }
        catch (\Stripe\Error\Authentication $e)
        {
            throw new PaymentApiCommunicationException($e->getMessage());
        }
        catch (\Stripe\Error\ApiConnection $e)
        {
            throw new PaymentApiCommunicationException($e->getMessage());
        }
        catch (\Stripe\Error\Base $e)
        {
            throw new PaymentApiCommunicationException($e->getMessage());
        }
    }
}