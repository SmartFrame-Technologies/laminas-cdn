<?php

namespace Smartframe\Cdn\Service\Dictionary;

class KeysCache
{
    private ?\stdClass $cache = null;

    public function clear()
    {
        $this->cache = null;
    }


    public function get(): ?\stdClass
    {
        return $this->cache;
    }

    public function store(?\stdClass $cache): void
    {
        $this->cache = $cache;
    }


}
