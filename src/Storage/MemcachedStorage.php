<?php

namespace Choccybiccy\Trip\Storage;

/**
 * Class MemcachedStorage
 * @package Choccybiccy\Trip\Storages
 */
class MemcachedStorage implements StorageInterface
{

    /**
     * @var \Memcached
     */
    protected $memcached;

    /**
     * @param array $servers
     */
    public function __construct($servers = [])
    {
        $memcached = new \Memcached;
        foreach($servers as $server) {
            $memcached->addServer($server[0], array_key_exists(1, $server) ? $server[1] : 11211);
        }
        $this->memcached = $memcached;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->memcached->set($key, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        $this->memcached->get($key);
    }

    /**
     * @return \Memcached
     */
    public function getMemcached()
    {
        return $this->memcached;
    }
}
