<?php

namespace Choccybiccy\Trip\Storage;

/**
 * Class ArrayStorage
 * @package Choccybiccy\Trip\Storage
 */
class ArrayStorage implements StorageInterface
{

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param string $key
     * @param mixed $value
     * @param int $expiration Not supported
     * @return $this
     * @throws \Exception
     */
    public function set($key, $value, $expiration = null)
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }
}
