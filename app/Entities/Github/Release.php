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
    private $repository;

    public function __construct(Request $request)
    {
        $release = $request->get('release');

        $this->link = $release['html_url'];
        $this->name = $release['name'];
        $this->tag = $release['tag_name'];
        $this->prerelease = $release['prerelease'];
        $this->tarball = $release['tarball_url'];
        $this->authorName = $release['author']['login'];
        $this->authorLink = $release['author']['html_url'];
        $this->authorIcon = $release['author']['avatar_url'];

        $repository = $request->get('repository');

        $this->repository = $repository['name'];
    }

    public function getRepository()
    {
        return $this->repository;
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
