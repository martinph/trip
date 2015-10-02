<?php

namespace Choccybiccy\Trip;

use Choccybiccy\Trip\Storage\StorageInterface;

/**
 * Class CircuitBreaker
 * @package Choccybiccy\Trip
 */
class CircuitBreaker
{

    const OPEN_KEY = "open";
    const CLOSED_KEY = "closed";

    /**
     * @var string
     */
    protected $commandKey;

    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var int
     */
    protected $storageExpiration;

    /**
     * @param string $commandKey
     * @param StorageInterface $storage
     * @param int|null $storageExpiration
     */
    public function __construct($commandKey, StorageInterface $storage, $storageExpiration = null)
    {
        $this->commandKey = $commandKey;
        $this->storage = $storage;
        $this->storageExpiration = $storageExpiration;
    }

    /**
     * Open circuit
     */
    public function open()
    {
        $this->getStorage()->set($this->storageKey(), self::OPEN_KEY, $this->storageExpiration);
    }

    /**
     * Close circuit
     */
    public function close()
    {
        $this->getStorage()->set($this->storageKey(), self::CLOSED_KEY, $this->storageExpiration);
    }

    /**
     * Is circuit open?
     *
     * @return bool
     */
    public function isOpen()
    {
        return $this->getStorage()->get($this->storageKey()) == self::OPEN_KEY ? true : false;
    }

    /**
     * @return string
     */
    public function getCommandKey()
    {
        return $this->commandKey;
    }

    /**
     * @return StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Get the storage key for this circuit breaker.
     *
     * @return string
     */
    public function storageKey()
    {
        return $this->getCommandKey() . "_CircuitBreaker";
    }
}
