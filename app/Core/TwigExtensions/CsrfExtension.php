<?php


namespace App\Core\TwigExtensions;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CsrfExtension extends AbstractExtension
{

	protected $guard;

	public function __construct($guard)
	{
		$this->guard = $guard;
	}

	public function getFunctions()
	{
		return [
			new TwigFunction("csrf", array($this, "csrf"))
		];
	}


	public function csrf()
	{
		return '
			<input type="hidden" name="'. $this->guard->getTokenNameKey() . '" value="'. $this->guard->getTokenName() .'"/>	
			<input type="hidden" name="'. $this->guard->getTokenValueKey() .'" value="'. $this->guard->getTokenValue() .'"/>	
		';
	}
}