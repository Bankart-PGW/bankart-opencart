<?php

namespace Bankart\Client\Transaction;

use Bankart\Client\Transaction\Base\AbstractTransaction;
use Bankart\Client\Transaction\Base\AddToCustomerProfileInterface;
use Bankart\Client\Transaction\Base\AddToCustomerProfileTrait;
use Bankart\Client\Transaction\Base\OffsiteInterface;
use Bankart\Client\Transaction\Base\OffsiteTrait;
use Bankart\Client\Transaction\Base\ScheduleInterface;
use Bankart\Client\Transaction\Base\ScheduleTrait;

/**
 * Register: Register the customer's payment data for recurring charges.
 *
 * The registered customer payment data will be available for recurring transaction without user interaction.
 *
 * @package Bankart\Client\Transaction
 */
class Register extends AbstractTransaction implements OffsiteInterface, ScheduleInterface, AddToCustomerProfileInterface {
    use OffsiteTrait;
    use ScheduleTrait;
    use AddToCustomerProfileTrait;
}
