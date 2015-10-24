<?php

namespace App\Console\Formatters;

class CliFormatter implements OutputFormatter
{
	public function format($message)
	{
		return $message;
	}
}
