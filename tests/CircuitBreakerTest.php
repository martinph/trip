<?php

namespace Choccybiccy\Trip;

use Choccybiccy\Trip\Storage\ArrayStorage;

/**
 * Class CircuitBreakerTest
 * @package Choccybiccy\Trip
 */
class CircuitBreakerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test open sets the appropriate open key in storage
     */
    public function testOpen()
    {

        $storage = $this->getMock('Choccybiccy\Trip\Storage\StorageInterface');
        $circuitBreaker = new CircuitBreaker("someKey", $storage);

        $storage->expects($this->once())
            ->method("set")
            ->with($circuitBreaker->storageKey(), CircuitBreaker::OPEN_KEY);

        $circuitBreaker->open();

    }

    /**
     * Test close sets the appropriate close key in storage
     */
    public function testClose()
    {

        $storage = $this->getMock('Choccybiccy\Trip\Storage\StorageInterface');
        $circuitBreaker = new CircuitBreaker("someKey", $storage);

        $storage->expects($this->once())
            ->method("set")
            ->with($circuitBreaker->storageKey(), CircuitBreaker::CLOSED_KEY);

        $circuitBreaker->close();

    }

    /**
     * Test isOpen returns true when open and false when closed
     */
    public function testIsOpen()
    {

        $circuitBreaker = new CircuitBreaker("someKey", new ArrayStorage);

        $circuitBreaker->open();
        $this->assertTrue($circuitBreaker->isOpen());

        $circuitBreaker->close();
        $this->assertFalse($circuitBreaker->isOpen());

    }

    /**
     * Test getCommandKey works as expected
     */
    public function testGetCommandKey()
    {
        $circuitBreaker = new CircuitBreaker("someKey", $this->getMock('Choccybiccy\Trip\Storage\StorageInterface'));
        $this->assertEquals("someKey", $circuitBreaker->getCommandKey());
    }

    /**
     * Test storageKey works as expected
     */
    public function testStorageKey()
    {
        $circuitBreaker = new CircuitBreaker("someKey", $this->getMock('Choccybiccy\Trip\Storage\StorageInterface'));
        $this->assertEquals("someKey_CircuitBreaker", $circuitBreaker->storageKey());
    }
}
