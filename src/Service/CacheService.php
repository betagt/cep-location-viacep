<?php
/**
 * Created by PhpStorm.
 * User: dsoft
 * Date: 31/01/2017
 * Time: 11:14
 */

namespace Begagt\Services;


class CacheService
{
    private $cache;

    public function __construct(\Illuminate\Cache\Repository $cache )
    {
        $this->cache = $cache;
    }

    public function getCache(){
        return $this->cache;
    }
    public function has($key){
        return $this->getCache()->has($key);
    }

    public function put($key,$value,$minuts=1){
        return $this->getCache()->put($key,$value,$minuts);
    }

    public function get($key, $default = null){
        return $this->getCache()->get($key, $default);
    }
}