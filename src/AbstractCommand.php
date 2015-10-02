<?php

namespace Choccybiccy\Trip;

use Aura\Di\Container;
use Choccybiccy\Trip\Exception\FallbackNotAvailableException;
use Choccybiccy\Trip\Storage\StorageInterface;

/**
 * Class AbstractCommand
 * @package Choccybiccy\Trip
 */
abstract class AbstractCommand
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var CircuitBreaker
     */
    protected $circuitBreaker;

    /**
     * @var StorageInterface
     */
    protected $cache;

    /**
     * @var string|null
     */
    protected $cacheKey;

    /**
     * @var int
     */
    protected $cacheExpirationInSeconds;

    /**
     * @var int
     */
    protected $circuitBreakerExpirationInSeconds;

    /**
     * Run the command
     *
     * @return mixed
     */
    abstract protected function run();

    /**
     * A key for this command. Used in storage keys and metrics.
     *
     * @return string
     */
    abstract public function getCommandKey();

    /**
     * Execute the command
     *
     * @return mixed
     */
    public function execute()
    {

        $cacheEnabled = $this->getCacheKey() !== null ? true : false;
        if ($cacheEnabled) {
            $cachedResult = $this->getCache()->get($this->storageKey());
            if ($cachedResult) {
                return $cachedResult;
            }
        }

        if ($this->getCircuitBreaker()->isOpen()) {
            return $this->getFallbackOrThrowException();
        }

        try {
            $result = $this->run();
            $this->getCircuitBreaker()->close();

        } catch (\Exception $e) {
            $this->getCircuitBreaker()->open();
            $result = $this->getFallbackOrThrowException($e);

        }

        if ($cacheEnabled) {
            $this->getCache()->set($this->storageKey(), $result, $this->cacheExpirationInSeconds);
        }

        return $result;

    }

    /**
     * @throws FallbackNotAvailableException
     */
    protected function getFallback()
    {
        throw new FallbackNotAvailableException("Fallback not available");
    }

    /**
     * @param \Exception|null $originalException
     * @throws \RuntimeException Fallback not available
     */
    protected function getFallbackOrThrowException(\Exception $originalException = null)
    {

        $message = $originalException === null ? "Circuit open" : $originalException->getMessage();

        try {
            $result = $this->getFallback();
            return $result;

        } catch (FallbackNotAvailableException $e) {
            throw new \RuntimeException(
                $message . " and fallback not available",
                get_class($this),
                $originalException
            );

        } catch (\Exception $e) {
            throw new \RuntimeException(
                $message . " and failed executing fallback",
                get_class($this),
                $originalException
            );

        }

    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param Container $container
     * @return $this
     */
    public function setContainer($container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return CircuitBreaker
     */
    public function getCircuitBreaker()
    {
        return $this->circuitBreaker;
    }

    /**
     * @param CircuitBreaker $circuitBreaker
     * @return $this
     */
    public function setCircuitBreaker($circuitBreaker)
    {
        $this->circuitBreaker = $circuitBreaker;
        return $this;
    }

    /**
     * If null then the cache is considered as disabled
     *
     * @return string|null
     */
    protected function getCacheKey()
    {
        return $this->cacheKey;
    }

    /**
     * @return StorageInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param StorageInterface $cache
     * @return $this
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * @return string
     */
    protected function storageKey()
    {
        return $this->getCommandKey() . "_" . $this->getCacheKey() . "_Result";
    }

    /**
     * @return int
     */
    public function getCacheExpirationInSeconds()
    {
        return $this->cacheExpirationInSeconds;
    }

    /**
     * @return int
     */
    public function getCircuitBreakerExpirationInSeconds()
    {
        return $this->circuitBreakerExpirationInSeconds;
    }
}
