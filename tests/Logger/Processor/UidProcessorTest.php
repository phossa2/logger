<?php

namespace Phossa2\Logger\Processor;

use Phossa2\Logger\Entry\LogEntry;

/**
 * UidProcessor test case.
 */
class UidProcessorTest extends \PHPUnit_Framework_TestCase
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
     * with predefined uid
     *
     * @covers Phossa2\Logger\Processor\UidProcessor::__invoke
     */
    public function test__invoke1()
    {
        $log = new LogEntry('test', 'debug', 'message {bingo}', ['bingo' => 'wow']);

        $int = new UidProcessor('uid');

        // run processor
        $int($log);

        $context = $log->getContext();

        $this->assertEquals('uid', $context['uid']);
    }

    /**
     * with generated uid
     *
     * @covers Phossa2\Logger\Processor\UidProcessor::__invoke
     */
    public function test__invoke2()
    {
        $log = new LogEntry('test', 'debug', 'message {bingo}', ['bingo' => 'wow']);

        $int = new UidProcessor();

        // run processor
        $int($log);

        $context = $log->getContext();

        $this->assertEquals(8, strlen($context['uid']));
    }
}

