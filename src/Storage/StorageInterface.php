<?php

namespace Choccybiccy\Trip\Storage;

/**
 * Interface StorageInterface
 * @package Choccybiccy\Trip\Storage
 */
interface StorageInterface
{

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value);

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key);
}
