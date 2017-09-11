<?php

namespace Wechat\Payment\Helper;

use Wechat\Payment\Exception\InvalidArgumentException;

class Attribute
{
	protected $items = [];

	public function __construct($params) {
		foreach($this->attributes as $key) {
			if(empty($params[$key])) {
				throw new InvalidArgumentException("The parameter {$key} can not be empty");
			}
			$this->items[$key] = $params[$key];
		}
	}

	public function all()
	{
		return $this->items;
	}

}