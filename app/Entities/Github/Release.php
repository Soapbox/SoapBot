<?php

namespace App\Entities\Github;

use Exception;
use Illuminate\Http\Request;

class Release
{
    private $link;
    private $name;
    private $tag;
    private $prerelease;
    private $tarball;
    private $authorName;
    private $authorLink;
    private $authorIcon;

    public function __construct(Request $request)
    {
        $temp = $request->get('release');

        $this->link = $temp['html_url'];
        $this->name = $temp['name'];
        $this->tag = $temp['tag_name'];
        $this->prerelease = $temp['prerelease'];
        $this->tarball = $temp['tarball_url'];
        $this->authorName = $temp['author']['login'];
        $this->authorLink = $temp['author']['html_url'];
        $this->authorIcon = $temp['author']['avatar_url'];
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAuthorName()
    {
        return $this->authorName;
    }

    public function getAuthorLink()
    {
        return $this->authorLink;
    }

    public function getAuthorIcon()
    {
        return $this->authorIcon;
    }

    public function getTag()
    {
        return $this->tag;
    }

    public function isPrerelease()
    {
        return $this->prerelease;
    }

    public function getTarball()
    {
        return $this->tarball;
    }
}
