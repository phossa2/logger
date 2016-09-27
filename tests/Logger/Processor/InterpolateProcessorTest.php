<?php

namespace Phossa2\Logger\Processor;

use Phossa2\Logger\Entry\LogEntry;

/**
 * InterpolateProcessor test case.
 */
class InterpolateProcessorTest extends \PHPUnit_Framework_TestCase
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
     * replace string
     *
     * @covers Phossa2\Logger\Processor\InterpolateProcessor::__invoke
     */
    public function test__invoke1()
    {
        $this->expectOutputRegex('/test\.DEBUG: message wow/');

        $log = new LogEntry('test', 'debug', 'message {bingo}', ['bingo' => 'wow']);
        $int = new InterpolateProcessor();

        // run processor
        $int($log);

        echo (string) $log;
    }

    /**
     * replace string.str
     *
     * @covers Phossa2\Logger\Processor\InterpolateProcessor::__invoke
     */
    public function test__invoke2()
    {
        $this->expectOutputRegex('/test\.DEBUG: message wow2/');

        $log = new LogEntry('test', 'debug', 'message {bingo.uid}',
            ['bingo' => [ 'uid' => 'wow2']]);

        $int = new InterpolateProcessor();

        // run processor
        $int($log);

        echo (string) $log;
    }
}

