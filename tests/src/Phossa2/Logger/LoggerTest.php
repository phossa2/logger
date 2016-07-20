<?php

namespace Phossa2\Logger;

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
     *
     * @covers Phossa2\Logger\Logger::log
     */
    public function testLog()
    {
    }
}
