<?php
/**
 * Phossa Project
 *
 * PHP version 5.4
 *
 * @category  Library
 * @package   Phossa2\Logger
 * @copyright Copyright (c) 2016 phossa.com
 * @license   http://mit-license.org/ MIT License
 * @link      http://www.phossa.com/
 */
/*# declare(strict_types=1); */

namespace Phossa2\Logger;

use Psr\Log\LoggerTrait;
use Psr\Log\LoggerInterface;
use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Logger\Traits\ExtendedLoggerTrait;
use Phossa2\Logger\Entry\LogEntryPrototypeTrait;
use Phossa2\Logger\Entry\LogEntryPrototypeInterface;

/**
 * Logger
 *
 * Implementation of LoggerInterface
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     LoggerInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Logger extends ObjectAbstract implements LoggerInterface, LogEntryPrototypeInterface
{
    use LoggerTrait, LogEntryPrototypeTrait, ExtendedLoggerTrait;

    /**
     * Must set current channel name EACH TIME!
     *
     * @param  string $channel
     * @return $this
     * @access public
     * @api
     */
    public function __invoke(/*# string */ $channel)
    {
        return $this->setChannel($channel);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param  mixed $level
     * @param  string $message
     * @param  array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        // make sure channel is set with $logger($channel)
        $this->isChannelSet();

        // create log entry
        $entry = $this->newLogEntry(
            $this->getChannel(), $level, $message, $context
        );

        // run processors
        $this->runProcessors($entry);

        // run handlers
        $this->runHandlers($entry);

        // mark channel unset
        $this->unsetChannel();
    }

    /**
     * Add handler to the channel with priority
     *
     * if $channel == '', use current logger channel
     *
     * @param  callable $handler
     * @param  string $channel channel to listen to
     * @param  int $priority
     * @return $this
     * @access public
     * @api
     */
    public function addHandler(
        callable $handler,
        /*# string */ $channel = '',
        /*# int */ $priority = 0
    ) {
        return $this->addCallable('handlers', $handler, $channel, $priority);
    }

    /**
     * Remove this handler from the channel
     *
     * if $channel == '', then remove this handler from all channels
     *
     * @param  callable $handler
     * @param  string $channel
     * @return $this
     * @access public
     * @api
     */
    public function removeHandler(callable $handler, $channel = '')
    {
        return $this->removeCallable('handlers', $handler, $channel);
    }

    /**
     * Add processor to the channel with priority
     *
     * if $channel == '', use current logger channel
     *
     * @param  callable $processor
     * @param  string $channel channel to listen to
     * @param  int $priority
     * @return $this
     * @access public
     * @api
     */
    public function addProcessor(
        callable $processor,
        /*# string */ $channel = '',
        /*# int */ $priority = 0
    ) {
        return $this->addCallable('processors', $processor, $channel, $priority);
    }

    /**
     * Remove this $processor from $channel
     *
     * if $channel == '', then remove processor from all channels
     *
     * @param  callable $processor
     * @param  string $channel
     * @return $this
     * @access public
     * @api
     */
    public function removeProcessor(callable $processor, $channel = '')
    {
        return $this->removeCallable('processors', $processor, $channel);
    }
}
