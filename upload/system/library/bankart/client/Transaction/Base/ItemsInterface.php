<?php

namespace Bankart\Client\Transaction\Base;
use Bankart\Client\Data\Item;

/**
 * Interface ItemsInterface
 *
 * @package Bankart\Client\Transaction\Base
 */
interface ItemsInterface {

    /**
     * @param Item[] $items
     * @return void
     */
    public function setItems($items);

    /**
     * @return Item[]
     */
    public function getItems();

    /**
     * @param Item $item
     * @return void
     */
    public function addItem($item);

}
