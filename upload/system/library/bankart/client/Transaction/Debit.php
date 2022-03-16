<?php

namespace Bankart\Client\Transaction;

use Bankart\Client\Transaction\Base\AbstractTransactionWithReference;
use Bankart\Client\Transaction\Base\AddToCustomerProfileInterface;
use Bankart\Client\Transaction\Base\AddToCustomerProfileTrait;
use Bankart\Client\Transaction\Base\AmountableInterface;
use Bankart\Client\Transaction\Base\AmountableTrait;
use Bankart\Client\Transaction\Base\ItemsInterface;
use Bankart\Client\Transaction\Base\ItemsTrait;
use Bankart\Client\Transaction\Base\OffsiteInterface;
use Bankart\Client\Transaction\Base\OffsiteTrait;
use Bankart\Client\Transaction\Base\ScheduleInterface;
use Bankart\Client\Transaction\Base\ScheduleTrait;

/**
 * Debit: Charge the customer for a certain amount of money. This could be once, but also recurring.
 *
 * @package Bankart\Client\Transaction
 */
class Debit extends AbstractTransactionWithReference implements AmountableInterface, OffsiteInterface, ItemsInterface, ScheduleInterface, AddToCustomerProfileInterface {
    use OffsiteTrait;
    use AmountableTrait;
    use ItemsTrait;
    use ScheduleTrait;
    use AddToCustomerProfileTrait;

    const TRANSACTION_INDICATOR_SINGLE = 'SINGLE';
    const TRANSACTION_INDICATOR_INITIAL = 'INITIAL';
    const TRANSACTION_INDICATOR_RECURRING = 'RECURRING';
    const TRANSACTION_INDICATOR_CARDONFILE = 'CARDONFILE';
    const TRANSACTION_INDICATOR_CARDONFILE_MERCHANT = 'CARDONFILE_MERCHANT';

    /**
     * @var bool
     */
    protected $withRegister = false;

    /**
     * @var string
     */
    protected $transactionIndicator;

    /**
     * @return boolean
     */
    public function isWithRegister() {
        return $this->withRegister;
    }

    /**
     * set true if you want to register a user vault together with the debit
     *
     * @param boolean $withRegister
     *
     * @return $this
     */
    public function setWithRegister($withRegister) {
        $this->withRegister = $withRegister;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionIndicator() {
        return $this->transactionIndicator;
    }

    /**
     * @param string $transactionIndicator
     */
    public function setTransactionIndicator($transactionIndicator) {
        $this->transactionIndicator = $transactionIndicator;
        return $this;
    }

}
