<?php

namespace Choccybiccy\Trip;
use Choccybiccy\Trip\Exception\FallbackNotAvailableException;

/**
 * Class AbstractCommand
 * @package Choccybiccy\Trip
 */
abstract class AbstractCommand
{

    /**
     * @var CircuitBreaker
     */
    protected $circuitBreaker;

    /**
     * @return mixed
     */
    abstract protected function run();

    public function execute()
    {

        try {

            $result = $this->run();

        } catch(\Exception $e) {

            $result = $this->getFallbackOrThrowException();

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

    protected function getFallbackOrThrowException()
    {

        try {

            $result = $this->getFallback();
            return $result;

        } catch(FallbackNotAvailableException $e) {

        }

    }

    /**
     * @return string
     */
    public function getCommandKey()
    {
        return get_class($this);
    }
}
