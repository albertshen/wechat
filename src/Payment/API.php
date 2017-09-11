<?php

namespace Wechat\Payment;

use Wechat\Payment\BaseAPI;

class API extends BaseAPI
{

    /**
     * Sandbox box mode.
     *
     * @var bool
     */
    protected $sandboxEnabled = false;

	protected $merchant;

    const API_HOST = 'https://api.mch.weixin.qq.com';
    // api
    const API_PAY_ORDER = '/pay/micropay';
    const API_PREPARE_ORDER = '/pay/unifiedorder';
    const API_QUERY = '/pay/orderquery';
    const API_CLOSE = '/pay/closeorder';
    const API_REVERSE = '/secapi/pay/reverse';
    const API_REFUND = '/secapi/pay/refund';
    const API_QUERY_REFUND = '/pay/refundquery';
    const API_DOWNLOAD_BILL = '/pay/downloadbill';
    const API_REPORT = '/payitil/report';

    const API_URL_SHORTEN = 'https://api.mch.weixin.qq.com/tools/shorturl';
    const API_AUTH_CODE_TO_OPENID = 'https://api.mch.weixin.qq.com/tools/authcodetoopenid';
    const API_SANDBOX_SIGN_KEY = 'https://api.mch.weixin.qq.com/sandboxnew/pay/getsignkey';

    // order id types.
    const TRANSACTION_ID = 'transaction_id';
    const OUT_TRADE_NO = 'out_trade_no';
    const OUT_REFUND_NO = 'out_refund_no';
    const REFUND_ID = 'refund_id';

    // bill types.
    const BILL_TYPE_ALL = 'ALL';
    const BILL_TYPE_SUCCESS = 'SUCCESS';
    const BILL_TYPE_REFUND = 'REFUND';
    const BILL_TYPE_REVOKED = 'REVOKED';

	public function __construct(Merchant $merchant) 
	{
		$this->merchant = $merchant;
	}

    /**
     * Wrap API.
     *
     * @param string $resource
     *
     * @return string
     */
    protected function wrapApi($resource)
    {
        return self::API_HOST.($this->sandboxEnabled ? '/sandboxnew' : '').$resource;
    }

    /**
     * Pay the order.
     *
     * @param Order $order
     *
     * @return \EasyWeChat\Support\Collection
     */
    public function pay(Order $order)
    {
        return $this->request($this->wrapApi(self::API_PAY_ORDER), $order->all());
    }

    /**
     * Prepare order to pay.
     *
     * @param Order $order
     *
     * @return \EasyWeChat\Support\Collection
     */
    public function prepare(Order $order)
    {
        return $this->request($this->wrapApi(self::API_PREPARE_ORDER), $order->all());
    }
}
