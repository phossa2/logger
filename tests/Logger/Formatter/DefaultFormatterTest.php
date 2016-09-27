<?php

namespace Phossa2\Logger\Formatter;

use Phossa2\Logger\Entry\LogEntry;

/**
 * DefaultFormatter test case.
 */
class DefaultFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LogEntry
     */
    private $log;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->log = new LogEntry('test', 'debug', 'debug message', ['a' => 'b']);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->log = null;
        parent::tearDown();
    }

    /**
     * Set different format
     *
     * @covers Phossa2\Logger\Formatter\DefaultFormatter::__construct
     */
    public function test__construct()
    {
        $this->expectOutputString('debug message');
        $formatter = new DefaultFormatter('%message%');
        $formatter($this->log);
        echo $this->log;
    }
}

