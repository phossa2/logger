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
use Phossa2\Logger\Message\Message;
use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Logger\Entry\LogEntryInterface;
use Phossa2\Logger\Traits\ExtendedLoggerTrait;
use Phossa2\Logger\Entry\LogEntryPrototypeTrait;
use Phossa2\Logger\Entry\LogEntryPrototypeInterface;
use Phossa2\Logger\Exception\InvalidArgumentException;

/**
 * Logger
 *
 * Implementation of LoggerInterface
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     LoggerInterface
 * @see     LogEntryPrototypeInterface
 * @version 2.0.0
 * @since   2.0.0 added
 * @since   2.0.1 updated addHandler() with level support
 */
class Logger extends ObjectAbstract implements LoggerInterface, LogEntryPrototypeInterface
{
    use LoggerTrait, LogEntryPrototypeTrait, ExtendedLoggerTrait;

    /**
     * Instantiate with default channel name and log entry prototype
     *
     * @param  string $channel default channel
     * @param  LogEntryInterface $entryPrototype if any
     * @access protected
     */
    public function __construct(
        /*# string */ $channel = 'LOGGER',
        LogEntryInterface $entryPrototype = null
    ) {
        $this->default_channel = strtoupper($channel);
        $this->setLogEntryPrototype($entryPrototype);
    }

    /**
     * Set/With current channel, followed by any log() method
     *
     * @param  string $channel current channel
     * @return $this
     * @access protected
     */
    public function with(/*# string */ $channel)
    {
        $this->current_channel = strtoupper($channel);
        return $this;
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
        // create log entry
        $entry = $this->newLogEntry(
            $this->getChannel(),
            $level,
            $message,
            $context
        );

        // run processors
        $this->runProcessors($entry);

        // run handlers
        $this->runHandlers($entry);

        // unset current channel
        $this->current_channel = null;
    }

    /**
     * Add handler to the channel with priority
     *
     * @param  string $level the level this handler is handling
     * @param  callable $handler
     * @param  string $channel channel to listen to
     * @param  int $priority
     * @return $this
     * @access public
     * @since  2.0.1 added level param
     * @api
     */
    public function addHandler(
        /*# string */ $level,
        callable $handler,
        /*# string */ $channel = '*',
        /*# int */ $priority = 0
    ) {
        // check level
        if (!isset(LogLevel::$levels[$level])) {
            throw new InvalidArgumentException(
                Message::get(Message::LOG_LEVEL_INVALID, $level),
                Message::LOG_LEVEL_INVALID
            );
        }

        return $this->addCallable(
            'handlers',
            $handler,
            $channel,
            $priority,
            $level
        );
    }

    /**
     * Remove this handler from the channel
     *
     * if $channel == '', then remove this handler from all channels
     *
     * @param  callable|string $handlerOrClassname
     * @param  string $channel
     * @return $this
     * @access public
     * @api
     */
    public function removeHandler($handlerOrClassname, $channel = '')
    {
        return $this->removeCallable('handlers', $handlerOrClassname, $channel);
    }

    /**
     * Add processor to the channel with priority
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
        /*# string */ $channel = '*',
        /*# int */ $priority = 0
    ) {
        return $this->addCallable(
            'processors',
            $processor,
            $channel,
            $priority
        );
    }

    /**
     * Remove this $processor from $channel
     *
     * if $channel == '', then remove processor from all channels
     *
     * @param  callable|string $processorOrClassname
     * @param  string $channel
     * @return $this
     * @access public
     * @api
     */
    public function removeProcessor($processorOrClassname, $channel = '')
    {
        return $this->removeCallable(
            'processors',
            $processorOrClassname,
            $channel
        );
    }
}
