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

/**
 * LogEntryPrototypeTrait
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     LogEntryPrototypeInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait LogEntryPrototypeTrait
{
    /**
     * log entry prototype
     *
     * @var    LogEntryInterface
     * @access protected
     */
    protected $entry_proto;

    /**
     * {@inheritDoc}
     */
    public function setLogEntryPrototype(LogEntryInterface $logEntry = null)
    {
        $this->entry_proto = $logEntry;
        return $this;
    }

    /**
     * Create a log entry
     *
     * @param  string $channel
     * @param  string $level
     * @param  string $message
     * $param  array $context
     * @return LogEntryInterface
     * @access protected
     */
    protected function newLogEntry(
        /*# string */ $channel,
        /*# string */ $level,
        /*# string */ $message,
        array $context = []
    )/*# : EventInterface */ {
        if (is_null($this->entry_proto)) {
            return new LogEntry($channel, $level, $message, $context);
        } else {
            $entry = clone $this->entry_proto;
            return $entry
                ->setChannel($channel)
                ->setLevel($level)
                ->setMessage($message)
                ->setContext($context)
                ->setTimestamp();
        }
    }
}
