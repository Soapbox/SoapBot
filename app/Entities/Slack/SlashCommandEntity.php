<?php

namespace App\Entities\Slack;

use Illuminate\Http\Request;

class SlashCommandEntity
{
	private $token;
	private $teamId;
	private $teamDomain;
	private $channelId;
	private $channelName;
	private $userId;
	private $userName;
	private $command;
	private $text;

	public function __construct(Request $request)
	{
		$this->token = $request->get('token');
		$this->teamId = $request->get('team_id');
		$this->teamDomain = $request->get('team_domain');
		$this->channelId = $request->get('channel_id');
		$this->channelName = $request->get('channel_name');
		$this->userId = $request->get('user_id');
		$this->userName = $request->get('user_name');
		$this->command = ltrim($request->get('command'), '/');
		$this->text = $request->get('text');
	}

	public function getToken()
	{
		return $this->token;
	}

	public function getTeamId()
	{
		return $this->teamId;
	}

	public function getTeamDomain()
	{
		return $this->teamDomain;
	}

	public function getChannelId()
	{
		return $this->channelId;
	}

	public function getChannelName()
	{
		return $this->channelName;
	}

	public function getUserId()
	{
		return $this->userId;
	}

	public function getUserName()
	{
		return $this->userName;
	}

	public function getCommand()
	{
		return $this->command;
	}

	public function getText()
	{
		return $this->text;
	}
}
