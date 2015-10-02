<?php

namespace Choccybiccy\Trip;

use Aura\Di\Container;
use Choccybiccy\Trip\Exception\CommandNotFoundException;
use Choccybiccy\Trip\Storage\StorageInterface;

/**
 * Class CommandFactory
 * @package Choccybiccy\Trip
 */
class CommandFactory
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var StorageInterface
     */
    protected $circuitBreakerStorage;

    /**
     * @var StorageInterface
     */
    protected $commandStorage;

    /**
     * @param Container $container
     * @param StorageInterface $circuitBreakerStorage
     * @param StorageInterface $commandStorage
     */
    public function __construct(
        Container $container,
        StorageInterface $circuitBreakerStorage,
        StorageInterface $commandStorage
    ) {
        $this->container = $container;
        $this->circuitBreakerStorage = $circuitBreakerStorage;
        $this->commandStorage = $commandStorage;
    }

    /**
     * @param string $command
     * @return AbstractCommand
     * @throws \Exception
     */
    public function getCommand($command)
    {

        if (class_exists($command)) {
            $arguments = func_get_args();
            array_shift($arguments);

            $class = new \ReflectionClass($command);
            if ($arguments !== null) {
                $arguments = [];
            }

            /** @var AbstractCommand $command */
            $command = $class->newInstanceArgs($arguments);

            $circuitBreaker = new CircuitBreaker($command->getCommandKey(), $this->circuitBreakerStorage);

            $command->setCache($this->commandStorage);
            $command->setContainer($this->container);
            $command->setCircuitBreaker($circuitBreaker);

            return $command;

        }

        throw new CommandNotFoundException("Command '$command' does not exist");

    }
}
