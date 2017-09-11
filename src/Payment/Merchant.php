<?php

namespace Wechat\Payment;

use Wechat\Payment\Helper\Attribute;
use Wechat\Payment\Exception\InvalidArgumentException;

/**
 * Class Merchant.
 *
 * @property string $app_id
 * @property string $merchant_id
 * @property string $key
 * @property string $sub_app_id
 * @property string $sub_merchant_id
 * @property string $ssl_cert_path
 * @property string $ssl_key_path
 * @property string $fee_type
 * @property string $device_info
 */
class Merchant extends Attribute
{
    /**
     * @var array
     */
    protected $attributes = [
        'app_id',
        'mch_id',
        'key',
        'ssl_cert_path',
        'ssl_key_path',
        'fee_type',
        'device_info',
    ];

    public function __get($name) {
        return $this->items[$name];
    }
}