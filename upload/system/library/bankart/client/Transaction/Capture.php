<?php

namespace Bankart\Client\Transaction;

use Bankart\Client\Transaction\Base\AbstractTransactionWithReference;
use Bankart\Client\Transaction\Base\AmountableInterface;
use Bankart\Client\Transaction\Base\AmountableTrait;
use Bankart\Client\Transaction\Base\ItemsInterface;
use Bankart\Client\Transaction\Base\ItemsTrait;

/**
 * Capture: Charge a previously preauthorized transaction.
 *
 * @package Bankart\Client\Transaction
 */
class Capture extends AbstractTransactionWithReference implements AmountableInterface, ItemsInterface {
    use AmountableTrait;
    use ItemsTrait;
}
