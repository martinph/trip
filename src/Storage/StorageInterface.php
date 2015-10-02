<?php

namespace Choccybiccy\Trip\Storage;

/**
 * Interface StorageInterface
 * @package Choccybiccy\Trip\Storage
 */
interface StorageInterface
{

    /**
     * @param string $key Storage key
     * @param mixed $value Value
     * @param int|null $expiration Expiration in seconds
     * @return $this
     */
    public function set($key, $value, $expiration = null);

    /**
     * @param string $key Storage key
     * @return mixed
     */
    public function get($key);
}
