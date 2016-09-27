<?php

namespace Phossa2\Logger\Processor;

use Phossa2\Logger\Entry\LogEntry;

/**
 * MemoryProcessor test case.
 */
class MemoryProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * check memory usage set
     *
     * @covers Phossa2\Logger\Processor\MemoryProcessor::__invoke
     */
    public function test__invoke1()
    {
        $log = new LogEntry('test', 'debug', 'message {bingo}', ['bingo' => 'wow']);

        $int = new MemoryProcessor();

        // run processor
        $int($log);

        $context = $log->getContext();

        $this->assertTrue(isset($context['memory']['used']));
    }
}

