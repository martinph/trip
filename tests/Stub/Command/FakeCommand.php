<?php

namespace Choccybiccy\Trip\Stub\Command;

use Choccybiccy\Trip\AbstractCommand;

/**
 * Class FakeCommand
 * @package Choccybiccy\Trip\Stub\Command
 */
class FakeCommand extends AbstractCommand
{

    /**
     * @return string
     */
    public function getCommandKey()
    {
        return "Fake";
    }

    /**
     * @return string
     */
    public function run()
    {
        return "fake";
    }
}
