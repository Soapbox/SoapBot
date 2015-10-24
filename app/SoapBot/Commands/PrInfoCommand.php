<?php

namespace App\SoapBot\Commands;

use GuzzleHttp\Client;

class PrInfoCommand
{
	private function parseSections($body) {
		$bodyComponents = preg_split('/#+\s+(\[[A-Za-z\s]+\]).*?\n/', $body, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

		$sectionLabel = '';
		$sections = [];
		foreach ($bodyComponents as $component) {
			$matches = [];
			if (preg_match('/^\[([A-Za-z\s]+)\]$/', $component, $matches)) {
				$sectionLabel = str_replace(' ', '_', strtolower($matches[1]));
				continue;
			}

			if (!empty($sectionLabel)) {
				$sections[$sectionLabel] = trim($component, " \r\n");
			}
		}

		return $sections;
	}

	public function execute($text)
	{
		$client = new Client();
		$response = $client->get('https://api.github.com/repos/SoapBox/soapbox-v4/pulls/' . $text,
			['query' => [
				'access_token' => '89d2ccb87582cce241e68d472e569a32f3bcf48b'
			]]
		);

		$pr = json_decode($response->getBody());

		$response = $client->request('GET', 'https://api.github.com/users/' . $pr->user->login,
			['query' => [
				'access_token' => '89d2ccb87582cce241e68d472e569a32f3bcf48b'
			]]
		);

		$author = json_decode($response->getBody());

		$header = sprintf('PR <%s|#%s> by *%s*', $pr->html_url, $pr->number, $author->name);

		$message = 'There was additional information specified for this pull request.';

		$sections = $this->parseSections($pr->body);
		if (array_key_exists('summary', $sections)) {
			$message = "*What does this PR do?*\r\n";
			$message .= $sections['summary'];
		}

		$message .= "\r\n\r\n";

		$message .= "*The following Jira tickets were closed by this PR*\r\n";
		if (array_key_exists('jira', $sections)) {
			$tickets = preg_split('/[\r\n]+/', $sections['jira'], -1, PREG_SPLIT_NO_EMPTY);
			foreach ($tickets as $ticket) {
				$message .= ltrim($ticket, '- ') . "\r\n";
			}
			$message = rtrim($message, "\r\n");
		} else {
			$message .= 'There are no Jira tickets closed by this PR';
		}

		return sprintf("%s\r\n\r\n%s", $header, $message);
	}
}
