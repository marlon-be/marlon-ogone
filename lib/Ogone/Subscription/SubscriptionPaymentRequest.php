<?php

namespace Ogone\Subscription;

use InvalidArgumentException;
use DateTime;
use Ogone\Ecommerce\EcommercePaymentRequest;

class SubscriptionPaymentRequest extends EcommercePaymentRequest
{

    /**
     * Set amount in cents, eg EUR 12.34 is written as 1234
     * For subscriptions an amount of 0 can be selected, however this feature must first be enabled by ogone for your account
     */
    public function setAmount($amount)
    {
        if (!is_int($amount)) {
            throw new InvalidArgumentException("Integer expected. Amount is always in cents");
        }
        if ($amount < 0) {
            throw new InvalidArgumentException("Amount must be a positive number or 0");
        }
        if ($amount >= 1.0E+15) {
            throw new InvalidArgumentException("Amount is too high");
        }

        $this->parameters['amount'] = $amount;

    }

    /**
     * Unique identifier of the subscription. The subscription id must be assigned dynamically.
     * @author René de Kat <renedekat@9lives-development.com>
     *
     * @param string $subscriptionId (maxlength 50)
     */
    public function setSubscriptionId($subscriptionId)
    {
        if (strlen($subscriptionId) > 50) {
            throw new InvalidArgumentException("Subscription id cannot be longer than 50 characters");
        }
        if (preg_match('/[^a-zA-Z0-9_-]/', $subscriptionId)) {
            throw new InvalidArgumentException("Subscription id cannot contain special characters");
        }
        $this->parameters['subscription_id'] = $subscriptionId;
    }

    /**
     * Amount of the subscription (can be different from the amount of the original transaction)
     * multiplied by 100, since the format of the amount must not contain any decimals or other separators.
     *
     * @author René de Kat <renedekat@9lives-development.com>
     *
     * @param integer $amount
     */
    public function setSubscriptionAmount($amount)
    {
        if (!is_int($amount)) {
            throw new InvalidArgumentException("Integer expected. Amount is always in cents");
        }
        if ($amount <= 0) {
            throw new InvalidArgumentException("Amount must be a positive number");
        }
        if ($amount >= 1.0E+15) {
            throw new InvalidArgumentException("Amount is too high");
        }
        $this->parameters['sub_amount'] = $amount;
    }

    /**
     * Order description
     * @author René de Kat <renedekat@9lives-development.com>
     *
     * @param string $description (maxlength 100)
     */
    public function setSubscriptionDescription($description)
    {
        if (strlen($description) > 100) {
            throw new InvalidArgumentException("Subscription description cannot be longer than 100 characters");
        }
        if (preg_match('/[^a-zA-Z0-9_ -]/', $description)) {
            throw new InvalidArgumentException("Subscription description cannot contain special characters");
        }
        $this->parameters['sub_com'] = $description;
    }

    /**
     * OrderID for subscription payments
     * @author René de Kat <renedekat@9lives-development.com>
     *
     * @param string $orderId (maxlength 40)
     */
    public function setSubscriptionOrderId($orderId)
    {
        if (strlen($orderId) > 40) {
            throw new InvalidArgumentException("Subscription order id cannot be longer than 40 characters");
        }
        if (preg_match('/[^a-zA-Z0-9_-]/', $orderId)) {
            throw new InvalidArgumentException("Subscription order id cannot contain special characters");
        }
        $this->parameters['sub_orderid'] = $orderId;
    }

    /**
     * Set subscription payment interval
     * @author René de Kat <renedekat@9lives-development.com>
     */
    public function setSubscriptionPeriod(SubscriptionPeriod $period)
    {
        $this->parameters['sub_period_unit'] = $period->getUnit();
        $this->parameters['sub_period_number'] = $period->getInterval();
        $this->parameters['sub_period_moment'] = $period->getMoment();
    }


    /**
     * Subscription start date
     * @author René de Kat <renedekat@9lives-development.com>
     *
     * @param DateTime $date    Startdate of the subscription.
     */
    public function setSubscriptionStartdate(DateTime $date)
    {
        $this->parameters['sub_startdate'] = $date->format('Y-m-d');
    }

    /**
     * Subscription end date
     * @author René de Kat <renedekat@9lives-development.com>
     *
     * @param DateTime $date    Enddate of the subscription.
     */
    public function setSubscriptionEnddate(DateTime $date)
    {
        $this->parameters['sub_enddate'] = $date->format('Y-m-d');
    }

    /**
     * Set subscription status
     * @author René de Kat <renedekat@9lives-development.com>
     *
     * @param integer $status   0 = inactive, 1 = active
     */
    public function setSubscriptionStatus($status)
    {
        if (!in_array($status, array(0, 1))) {
            throw new InvalidArgumentException("Invalid status specified for subscription. Possible values: 0 = inactive, 1 = active");
        }
        $this->parameters['sub_status'] = $status;
    }

    /**
     * Set comment for merchant
     * @author René de Kat <renedekat@9lives-development.com>
     *
     * @param string $comment
     */
    public function setSubscriptionComment($comment)
    {
        if (strlen($comment) > 200) {
            throw new InvalidArgumentException("Subscription comment cannot be longer than 200 characters");
        }
        if (preg_match('/[^a-zA-Z0-9_ -]/', $comment)) {
            throw new InvalidArgumentException("Subscription comment cannot contain special characters");
        }
        $this->parameters['sub_comment'] = $comment;
    }

    public function getRequiredFields()
    {
        return array(
            'pspid', 'currency', 'orderid',
            'subscription_id', 'sub_amount', 'sub_com', 'sub_orderid', 'sub_period_unit',
            'sub_period_number', 'sub_period_moment','sub_startdate', 'sub_status'
        );
    }
}
