<?php

namespace App\Services;

use Psr\Container\ContainerInterface;

class Service
{
	protected $container;

	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	public function __get($prop)
	{
		if ($this->container->{$prop}) {
			return $this->container->{$prop};
		}
	}
}