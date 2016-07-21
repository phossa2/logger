<?php

namespace Phossa2\Logger;

use Phossa2\Logger\Handler\EchoHandler;
use Phossa2\Logger\Formatter\DefaultFormatter;
use Phossa2\Logger\Processor\CounterProcessor;
use Phossa2\Logger\Processor\InterpolateProcessor;
use Phossa2\Logger\Entry\MyLogEntry;

/**
 * Logger test case.
 */
class LoggerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var    Logger
     * @access protected
     */
    protected $object;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->object = new Logger();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->object = null;
    }

    /**
     * Call protected/private method of a class.
     *
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    protected function invokeMethod($methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass($this->object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($this->object, $parameters);
    }

    /**
     * @covers Phossa2\Logger\Logger::log
     */
    public function testLog()
    {
        $this->expectOutputRegex('/LOGGER\.DEBUG: test/');

        // add echoHandler
        $this->object->addHandler(new EchoHandler());

        // debug
        $this->object->debug('test');
    }

    /**
     * @covers Phossa2\Logger\Logger::with
     */
    public function testWith()
    {
        $this->expectOutputRegex('/TEST\.WARNING: test/');

        // add echoHandler
        $this->object->addHandler(new EchoHandler());

        // debug
        $this->object->with('test')->warning('test');
    }

    /**
     * Test handler ordering
     *
     * @covers Phossa2\Logger\Logger::addHandler
     */
    public function testAddHandler1()
    {
        $this->expectOutputString("231");

        // add echoHandler 1
        $this->object->addHandler(
            new EchoHandler('debug', new DefaultFormatter('1')),
            '*', -10
        );

        // add echoHandler 2
        $this->object->addHandler(
            new EchoHandler('debug', new DefaultFormatter('2')),
            '*', 10
        );

        // add echoHandler 3
        $this->object->addHandler(
            new EchoHandler('debug', new DefaultFormatter('3')),
            '*', 10
        );

        // debug
        $this->object->with('test')->debug('test');
    }

    /**
     * Test handler level handlering
     *
     * @covers Phossa2\Logger\Logger::addHandler
     */
    public function testAddHandler2()
    {
        $this->expectOutputString("21");

        // add echoHandler 1
        $this->object->addHandler(
            new EchoHandler('debug', new DefaultFormatter('1')),
            '*', -10
        );

        // add echoHandler 2
        $this->object->addHandler(
            new EchoHandler('warning', new DefaultFormatter('2')),
            '*', 10
        );

        // add echoHandler 3
        $this->object->addHandler(
            new EchoHandler('error', new DefaultFormatter('3')),
            '*', 10
        );

        // warning
        $this->object->with('test')->warning('test');
    }

    /**
     * Test handler channel pattern
     *
     * @covers Phossa2\Logger\Logger::addHandler
     */
    public function testAddHandler3()
    {
        $this->expectOutputString("23");

        // add echoHandler 1
        $this->object->addHandler(
            new EchoHandler('debug', new DefaultFormatter('1')),
            'user.*', -10
        );

        // add echoHandler 2
        $this->object->addHandler(
            new EchoHandler('debug', new DefaultFormatter('2')),
            '*.watch', 10
        );

        // add echoHandler 3
        $this->object->addHandler(
            new EchoHandler('debug', new DefaultFormatter('3')),
            'system.*', 10
        );

        // warning
        $this->object->with('system.watch')->warning('test');
    }

    /**
     * Test remove handler by object or classname
     *
     * @covers Phossa2\Logger\Logger::removeHandler
     */
    public function testRemoveHandler()
    {
        $this->expectOutputString("1test1test");

        $handler = new EchoHandler('debug', new DefaultFormatter('1%message%'));

        // add echoHandler 1
        $this->object->addHandler($handler, 'user.*', -10);

        // add echoHandler 2
        $this->object->addHandler($handler, '*.login', 10);

        $this->object->with('user.login')->warning('test');

        // remove 1
        $this->object->removeHandler($handler, '*.login');

        // warning
        $this->object->with('user.login')->warning('test');

        // remove all by classname
        $this->object->removeHandler($handler::getClassName());

        $this->object->with('user.login')->warning('test');
    }

    /**
     * Test add processor with different channel & order
     *
     * @covers Phossa2\Logger\Logger::addProcessor
     */
    public function testAddProcessor1()
    {
        $this->expectOutputString("12{counter}");

        $h = new EchoHandler('debug', new DefaultFormatter('%message%'));

        // add processors
        $counter = new CounterProcessor();

        // only count user actions
        $this->object->addProcessor($counter, 'user.*');

        // interpolate MUST BE THE LAST processor
        $this->object->addProcessor(new InterpolateProcessor(), '*', -100);

        // add handlers
        $this->object->addHandler($h);


        $this->object->with('user.login')->debug('{counter}');
        $this->object->with('user.write')->debug('{counter}');
        $this->object->debug('{counter}');
    }

    /**
     * Test remove processor
     *
     * @covers Phossa2\Logger\Logger::removeProcessor
     */
    public function testRemoveProcessor1()
    {
        $this->expectOutputString("3{counter}4{counter}");

        $h = new EchoHandler('debug', new DefaultFormatter('%message%'));

        // add processors
        $counter = new CounterProcessor();

        // only count user actions
        $this->object->addProcessor($counter);

        // interpolate MUST BE THE LAST processor
        $this->object->addProcessor(new InterpolateProcessor(), '*', -100);

        // add handlers
        $this->object->addHandler($h);

        $this->object->debug('{counter}');

        $this->object->removeProcessor($counter);
        $this->object->debug('{counter}');

        $this->object->addProcessor($counter);
        $this->object->debug('{counter}');

        $this->object->removeProcessor(InterpolateProcessor::getClassName());
        $this->object->debug('{counter}');
    }

    /**
     * Test set log entry prototype
     *
     * @covers Phossa2\Logger\Logger::setLogEntryPrototype
     */
    public function testSetLogEntryPrototype()
    {
        require_once __DIR__ . '/Entry/MyLogEntry.php';

        $this->object->setLogEntryPrototype(new MyLogEntry('TEST', 'debug', ''));

        $this->object->addHandler(
            new EchoHandler('debug', new DefaultFormatter('%message%'))
        );

        $this->expectOutputString("MyLogEntry: test");
        $this->object->debug('test');
    }
}
