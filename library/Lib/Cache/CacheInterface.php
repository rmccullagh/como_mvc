<?php

namespace Lib\Cache;

interface CacheInterface
{
    public function get($key);
    public function set($key, $data, $expire);
    public function flush();
}
