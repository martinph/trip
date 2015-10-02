<?php

namespace Choccybiccy\Trip\Storage;

/**
 * Class ArrayStorageTest
 * @package Choccybiccy\Trip\Storage
 */
class ArrayStorageTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test set and get
     */
    public function testSetGet()
    {

        $storage = new ArrayStorage();

        $this->assertNull($storage->get("test"));

        $storage->set("test", "someValue");
        $this->assertEquals("someValue", $storage->get("test"));

    }
}
