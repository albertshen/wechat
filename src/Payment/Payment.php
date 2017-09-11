<?php

namespace Wechat\Payment;

use Wechat\Payment\Merchant;
use Wechat\Payment\API;

class Payment
{

    /**
     * @var API
     */
    private $api;
    
	private $payment;

	private $merchant;

	public function __construct($option) 
	{
		$this->setMerchant(new Merchant($option));
	}

    /**
     * Merchant setter.
     *
     * @param Merchant $merchant
     */
    public function setMerchant(Merchant $merchant)
    {
        $this->merchant = $merchant;
    }

    /**
     * Merchant getter.
     *
     * @return Merchant
     */
    public function getMerchant()
    {
        return $this->merchant;
    }

	public function __get($name) {
		if($name == 'merchant') {
			return $this->merchant;
		}
	}

    /**
     * API setter.
     *
     * @param API $api
     */
    public function setAPI(API $api)
    {
        $this->api = $api;
    }

    /**
     * Return API instance.
     *
     * @return API
     */
    public function getAPI()
    {
        return $this->api ? : $this->api = new API($this->getMerchant());
    }

    /**
     * Magic call.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     *
     * @codeCoverageIgnore
     */
    public function __call($method, $args)
    {
        if (is_callable([$this->getAPI(), $method])) {
            return call_user_func_array([$this->api, $method], $args);
        }
    }
}