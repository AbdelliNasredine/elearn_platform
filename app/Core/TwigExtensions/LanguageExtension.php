<?php


namespace App\Core\TwigExtensions;


use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class LanguageExtension extends AbstractExtension
{
	public function __construct($container)
	{
		$this->container = $container;
		$this->config = $container["lang"];
		$this->translator = $container["translator"];
	}

	public function getFunctions()
	{
		return [
			new TwigFunction("current_lang", array($this, "getCurrentLanguage")),
			new TwigFunction("available_langs", array($this, "getAvailableLanguages"))
		];
	}

	public function getCurrentLanguage()
	{
		return $this->container->language;
	}

	public function getAvailableLanguages()
	{
		return $this->config["available"];
	}

}