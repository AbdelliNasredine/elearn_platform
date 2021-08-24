<?php

namespace App\Core\TwigExtensions;

use Carbon\Carbon;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class DateExtension extends AbstractExtension
{

	public function getFunctions()
	{
		return [
			new TwigFunction("human_diff", [$this, "humanDiff"]),
		];
	}

	public function getFilters()
	{
		return [
			new TwigFilter("human_diff", [$this, "humanDiff"])
		];
	}

	public function humanDiff($start_date, $end_date = null)
	{
		return Carbon::parse($start_date)->diffForHumans();
	}
}