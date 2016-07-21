<?php

namespace Phossa2\Logger\Formatter;

use Phossa2\Logger\Entry\LogEntry;

/**
 * AnsiColorFormatter test case.
 */
class AnsiColorFormatterTest extends \PHPUnit_Framework_TestCase
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
        $this->log = new LogEntry('test', 'notice', 'debug message', ['a' => 'b']);
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
     * @covers Phossa2\Logger\Formatter\AnsiColorFormatter::__construct
     */
    public function test__construct()
    {
        $formatter = new AnsiColorFormatter(new DefaultFormatter('wow %message%'));
        $formatter($this->log);
        $this->assertEquals(
            "\033[1;32mwow debug message\033[0m", (string) $this->log
        );
    }
}
