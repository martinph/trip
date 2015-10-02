<?php

namespace Choccybiccy\Trip;

use Aura\Di\Container;
use Aura\Di\Factory;

/**
 * Class CommandFactoryTest
 * @package Choccybiccy\Trip
 */
class CommandFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test getCommand returns an instance of a command
     */
    public function testGetCommand()
    {

        $container = new Container(new Factory);
        $storage = $this->getMock('Choccybiccy\Trip\Storage\StorageInterface');
        $factory = new CommandFactory($container, $storage, $storage);
        $command = $factory->getCommand('Choccybiccy\Trip\Stub\Command\FakeCommand');

        $this->assertInstanceOf('Choccybiccy\Trip\AbstractCommand', $command);
        $this->assertInstanceOf('Aura\Di\ContainerInterface', $command->getContainer());
        $this->assertInstanceOf('Choccybiccy\Trip\Storage\StorageInterface', $command->getCache());


    }

    /**
     * Test getCommand throws an exception when the command doesn't exist
     */
    public function testGetCommandThrowsExceptionWhenCommandDoesntExists()
    {

        $this->setExpectedException('Choccybiccy\Trip\Exception\CommandNotFoundException');

        $container = new Container(new Factory);
        $storage = $this->getMock('Choccybiccy\Trip\Storage\StorageInterface');
        $factory = new CommandFactory($container, $storage, $storage);
        $factory->getCommand('Choccybiccy\Trip\Stub\Command\SomeNonExistentCommand');

    }
}
