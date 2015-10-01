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
     * @param string $commandKey
     * @param StorageInterface $storage
     */
    public function __construct($commandKey, StorageInterface $storage)
    {
        $this->commandKey = $commandKey;
        $this->storage = $storage;
    }

    /**
     * Open circuit
     */
    public function open()
    {
        $this->getStorage()->set($this->storageKey(), self::OPEN_KEY);
    }

    /**
     * Close circuit
     */
    public function close()
    {
        $this->getStorage()->set($this->storageKey(), self::CLOSED_KEY);
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
    protected function storageKey()
    {
        return $this->commandKey . "_CircuitBreaker";
    }
}
