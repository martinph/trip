<?php

namespace Choccybiccy\Trip;

use Choccybiccy\Trip\Traits\ReflectionMethods;

/**
 * Class AbstractCommandTest
 * @package Choccybiccy\Trip
 */
class AbstractCommandTest extends \PHPUnit_Framework_TestCase
{

    use ReflectionMethods;

    /**
     * Test storageKey
     */
    public function testStorageKey()
    {
        $commandKey = "testCommandKey";
        $cacheKey = "testCacheKey";

        $command = $this->getMockCommand(["getCommandKey"]);
        $command->expects($this->once())
            ->method("getCommandKey")
            ->willReturn($commandKey);
        $this->setProtectedProperty($command, "cacheKey", $cacheKey);

        $this->assertEquals(
            $commandKey . "_" . $cacheKey . "_Result",
            $this->runProtectedMethod($command, "storageKey")
        );
    }

    /**
     * Test set/get container
     */
    public function testSetGetContainer()
    {
        $container = $this->getMock('Aura\Di\ContainerInterface');
        $command = $this->getMockCommand();
        $command->setContainer($container);
        $this->assertEquals($container, $command->getContainer());
    }

    /**
     * Test set/get circuit breaker
     */
    public function testSetGetCircuitBreaker()
    {
        $circuitBreaker = new CircuitBreaker("test", $this->getMock('Choccybiccy\Trip\Storage\StorageInterface'));
        $command = $this->getMockCommand();
        $command->setCircuitBreaker($circuitBreaker);
        $this->assertEquals($circuitBreaker, $command->getCircuitBreaker());
    }

    /**
     * Test set/get cache
     */
    public function testSetGetCache()
    {
        $cache = $this->getMock('Choccybiccy\Trip\Storage\StorageInterface');
        $command = $this->getMockCommand();
        $command->setCache($cache);
        $this->assertEquals($cache, $command->getCache());
    }

    /**
     * Test getFallback throws exception when it's not overriden
     */
    public function testGetFallbackThrowsExceptionIfNotOverriden()
    {
        $this->setExpectedException('Choccybiccy\Trip\Exception\FallbackNotAvailableException');
        $command = $this->getMockCommand();
        $this->runProtectedMethod($command, "getFallback");
    }

    /**
     * @param array $methods
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockCommand($methods = [])
    {
        return $this->getMockBuilder('Choccybiccy\Trip\AbstractCommand')
            ->setMethods($methods)
            ->getMockForAbstractClass();
    }
}