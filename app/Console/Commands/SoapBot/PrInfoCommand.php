<?php

namespace App\Console\Commands\SoapBot;

use GuzzleHttp\Client;
use App\Console\Commands\SlackCommand;
use Symfony\Component\Console\Input\InputArgument;

class PrInfoCommand extends SlackCommand
{
	/**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'pr-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch additional information for a pull request';

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

	public function fire()
    {
    	$text = $this->argument('pr_number');

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

		$this->line(sprintf('PR <%s|#%s> - %s - by *%s*', $pr->html_url, $pr->number, $pr->title, $author->name));
		$this->line('');

		$this->line('*What does this PR do?*');;

		$sections = $this->parseSections($pr->body);
		if (array_key_exists('summary', $sections)) {
			$this->line($sections['summary']);
		} else {
			$this->line('There was no additional information specified for this pull request.');
		}

		$this->line('');

		$this->line('*The following Jira tickets were closed by this PR*');
		if (array_key_exists('jira', $sections)) {
			$tickets = preg_split('/[\r\n]+/', $sections['jira'], -1, PREG_SPLIT_NO_EMPTY);
			foreach ($tickets as $ticket) {
				$this->line(ltrim($ticket, '- '));
			}
		} else {
			$this->line('There are no Jira tickets closed by this PR');
		}
    }

	protected function getArguments()
	{
		return [
			['pr_number', InputArgument::REQUIRED, 'The pull request number'],
		];
	}
}
