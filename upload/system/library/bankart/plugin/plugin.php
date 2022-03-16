<?php

namespace Bankart;

final class BankartPlugin
{
    const METHOD_DEBIT = 'debit';
    const METHOD_PREAUTHORIZE = 'preauthorize';
    const PREFIX = 'payment_bankart';

    public function getVersion()
    {
        return '1.4.0';
    }

    public function getName()
    {
        return 'Bankart Payment Gateway OpenCart Extension';
    }

    public function getShopName()
    {
        return 'OpenCart';
    }

    public function getShopVersion()
    {
        return VERSION;
    }

    public function getTemplateData()
    {
        return [
            'plugin_name' => self::getName(),
            'plugin_version' => self::getVersion(),
        ];
    }
}
