<?php

namespace Bankart\Client\CustomerProfile;

use Bankart\Client\Json\ResponseObject;

/**
 * Class GetProfileResponse
 *
 * @package Bankart\Client\CustomerProfile
 *
 * @property bool $profileExists
 * @property string $profileGuid
 * @property string $customerIdentification
 * @property string $preferredMethod
 * @property CustomerData $customer
 * @property PaymentInstrument[] $paymentInstruments
 */
class GetProfileResponse extends ResponseObject {

}
