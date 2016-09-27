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

namespace Phossa2\Logger\Entry;

use Phossa2\Logger\LogLevel;
use Phossa2\Logger\Message\Message;
use Psr\Log\LogLevel as PsrLogLevel;
use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Logger\Formatter\DefaultFormatter;
use Phossa2\Logger\Exception\InvalidArgumentException;

/**
 * LogEntry
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     LogEntryInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class LogEntry extends ObjectAbstract implements LogEntryInterface
{
    /**
     * @var    string
     * @access protected
     */
    protected $channel;

    /**
     * @var    string
     * @access protected
     */
    protected $message;

    /**
     * @var    string
     * @access protected
     */
    protected $level;

    /**
     * @var    array
     * @access protected
     */
    protected $context;

    /**
     * @var    string
     * @access protected
     */
    protected $formatted;

    /**
     * @var    float
     * @access protected
     */
    protected $timestamp;

    /**
     * is log stopped propagation ?
     *
     * @var    bool
     * @access protected
     */
    protected $stopped = false;

    /**
     * Create the log entry
     *
     * @param  string $channel
     * @param  string $level
     * @param  string $message
     * @param  array $context
     * @param  float $timestamp
     * @throws InvalidArgumentException if level invalid
     * @access protected
     */
    public function __construct(
        /*# string */ $channel,
        /*# string */ $level,
        /*# string */ $message,
        array $context = [],
        /*# float */ $timestamp = 0
    ) {
        $this
            ->setChannel($channel)
            ->setLevel($level)
            ->setMessage($message)
            ->setContext($context)
            ->setTimestamp($timestamp);
    }

    /**
     * {@inheritDoc}
     */
    public function setChannel(
        /*# string */ $channel
    ) {
        $this->channel = $channel;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getChannel()/*# : string */
    {
        return $this->channel;
    }

    /**
     * {@inheritDoc}
     */
    public function setMessage(
        /*# string */ $message
    ) {
        $this->message = (string) $message;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage()/*# : string */
    {
        return $this->message;
    }

    /**
     * {@inheritDoc}
     */
    public function setLevel(
        /*# string */ $level = PsrLogLevel::INFO
    ) {
        if (!isset(LogLevel::$levels[$level])) {
            throw new InvalidArgumentException(
                Message::get(Message::LOG_LEVEL_INVALID, $level),
                Message::LOG_LEVEL_INVALID
            );
        }
        $this->level = $level;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getLevel()/*# : string */
    {
        return $this->level;
    }

    /**
     * {@inheritDoc}
     */
    public function setFormatted(/*# string */ $formatted)
    {
        $this->formatted = $formatted;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFormatted()/*# string */
    {
        if (null === $this->formatted) {
            $formatter = new DefaultFormatter();
            $formatter($this);
        }
        return $this->formatted;
    }

    /**
     * {@inheritDoc}
     */
    public function setTimestamp(
        /*# : float */ $timestamp = 0
    ) {
        $this->timestamp = (float) ($timestamp ?: microtime(true));
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getTimestamp()/*# : float */
    {
         return $this->timestamp;
    }

    /**
     * {@inheritDoc}
     */
    public function setContext(array $context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getContext()/*# : array */
    {
        return $this->context;
    }

    /**
     * {@inheritDoc}
     */
    public function stopPropagation(/*# : bool */ $flag = true)
    {
        $this->stopped = (bool) $flag;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isPropagationStopped()/*# : bool */
    {
        return $this->stopped;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()/*# : string */
    {
        return $this->getFormatted();
    }
}
