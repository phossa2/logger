<?php

namespace Phossa2\Logger\Entry;

/**
 * LogEntry test case.
 */
class LogEntryTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var LogEntry
     */
    private $object;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->object = new LogEntry('TEST', 'warning', 'message body');
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->object = null;
        parent::tearDown();
    }

    /**
     * @covers Phossa2\Logger\Entry\LogEntry::setChannel()
     */
    public function testSetChannel()
    {
        $this->object->setChannel('bingo');
        $this->assertEquals('bingo', $this->object->getChannel());
    }

    /**
     * @covers Phossa2\Logger\Entry\LogEntry::getChannel()
     */
    public function testGetChannel()
    {
        $this->assertEquals('TEST', $this->object->getChannel());
    }

    /**
     * @covers Phossa2\Logger\Entry\LogEntry::setMessage()
     */
    public function testSetMessage()
    {
        $this->object->setMessage('wow');
        $this->assertEquals('wow', $this->object->getMessage());
    }

    /**
     * @covers Phossa2\Logger\Entry\LogEntry::getMessage()
     */
    public function testGetMessage()
    {
        $this->assertEquals('message body', $this->object->getMessage());
    }

    /**
     * @covers Phossa2\Logger\Entry\LogEntry::setLevel()
     */
    public function testSetLevel1()
    {
        $this->object->setLevel('debug');
        $this->assertEquals('debug', $this->object->getLevel());
    }

    /**
     * @covers Phossa2\Logger\Entry\LogEntry::setLevel()
     * @expectedExceptionCode Phossa2\Logger\Message\Message::LOG_LEVEL_INVALID
     * @expectedException Phossa2\Logger\Exception\InvalidArgumentException
     */
    public function testSetLevel2()
    {
        $this->object->setLevel('debug2');
    }

    /**
     * @covers Phossa2\Logger\Entry\LogEntry::getLevel()
     */
    public function testGetLevel()
    {
        $this->assertEquals('warning', $this->object->getLevel());
    }

    /**
     * @covers Phossa2\Logger\Entry\LogEntry::setFormatted()
     */
    public function testSetFormatted()
    {
        $this->object->setFormatted('formatted');
        $this->assertEquals('formatted', $this->object->getFormatted());
    }

    /**
     * @covers Phossa2\Logger\Entry\LogEntry::getFormatted()
     */
    public function testGetFormatted()
    {
        $this->object->setFormatted('formatted');
        $this->assertEquals('formatted', $this->object->getFormatted());
    }

    /**
     * @covers Phossa2\Logger\Entry\LogEntry::setTimestamp()
     * @covers Phossa2\Logger\Entry\LogEntry::getTimestamp()
     */
    public function testSetTimestamp1()
    {
        $this->object->setTimestamp(123);
        $this->assertTrue(123 == (int) $this->object->getTimestamp());
    }

    /**
     * @covers Phossa2\Logger\Entry\LogEntry::setTimestamp()
     */
    public function testSetTimestamp2()
    {
        $this->object->setTimestamp(0);
        $now = microtime(true);
        $this->assertTrue($now - $this->object->getTimestamp() < 0.001);
    }

    /**
     * @covers Phossa2\Logger\Entry\LogEntry::getContext()
     */
    public function testGetContext()
    {
        $this->assertEquals([], $this->object->getContext());
    }

    /**
     * @covers Phossa2\Logger\Entry\LogEntry::setContext()
     */
    public function testSetContext()
    {
        $this->object->setContext(['wow' => 1]);
        $this->assertEquals(['wow' => 1], $this->object->getContext());
    }

    /**
     * @covers Phossa2\Logger\Entry\LogEntry::stopPropagation()
     * @covers Phossa2\Logger\Entry\LogEntry::isPropagationStopped()
     */
    public function testStopPropagation()
    {
        $this->assertFalse($this->object->isPropagationStopped());
        $this->object->stopPropagation();
        $this->assertTrue($this->object->isPropagationStopped());
        $this->object->stopPropagation(false);
        $this->assertFalse($this->object->isPropagationStopped());
    }

    /**
     * @covers Phossa2\Logger\Entry\LogEntry::__toString()
     */
    public function test__toString()
    {
        $this->expectOutputRegex('/TEST\.WARNING: message body/');
        echo (string) $this->object;
    }
}

