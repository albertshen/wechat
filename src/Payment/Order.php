<?php

namespace Wechat\Payment;

use Wechat\Payment\Helper\Attribute;
use Wechat\Payment\Exception\InvalidArgumentException;

class Order extends Attribute
{
    /**
     * @var array
     */
    protected $attributes = [
        'body',
        'detail',
        'attach',
        'out_trade_no',
        'fee_type',
        'total_fee',
        'spbill_create_ip',
        'time_start',
        'time_expire',
        'goods_tag',
        'notify_url',
        'trade_type',
        'product_id',
        'limit_pay',
        'openid',
        'sub_openid',
        'auth_code',
    ];


}