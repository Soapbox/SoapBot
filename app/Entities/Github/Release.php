<?php

namespace App\Entities\Github;

use Exception;
use Illuminate\Http\Request;

class Release
{
    private $name;
    private $tag;
    private $prerelease;
    private $tarball;

    public function __construct(Request $request)
    {
        $temp = $request->get('release');

        $this->name = $temp['name'];
        $this->tag = $temp['tag_name'];
        $this->prerelease = $temp['prerelease'];
        $this->tarball = $temp['tarball_url'];
    }

    public function getName()
    {
        return $this->name;
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
