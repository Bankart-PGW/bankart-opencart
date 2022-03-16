<?php

namespace Bankart\Client\CustomerProfile;

use Bankart\Client\Json\ResponseObject;

/**
 * Class UpdateProfileResponse
 *
 * @package Bankart\Client\CustomerProfile
 *
 * @property string $profileGuid
 * @property string $customerIdentification
 * @property CustomerData $customer
 * @property array $changedFields
 */
class UpdateProfileResponse extends ResponseObject {

}
