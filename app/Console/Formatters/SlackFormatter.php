<?php

namespace App\Console\Formatters;

class SlackFormatter implements OutputFormatter
{
	public function format($message)
	{
		$message = preg_replace('/<\/?((info)|(comment))>/', '*', $message);
		return preg_replace('/<\/?error>/', '~', $message);
	}
}
