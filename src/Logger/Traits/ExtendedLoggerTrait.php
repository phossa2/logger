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

namespace Phossa2\Logger\Traits;

use Phossa2\Logger\LogLevel;
use Phossa2\Shared\Queue\PriorityQueue;
use Phossa2\Shared\Globbing\GlobbingTrait;
use Phossa2\Logger\Entry\LogEntryInterface;
use Phossa2\Shared\Queue\PriorityQueueInterface;

/**
 * ExtendedLoggerTrait
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     interface
 * @version 2.0.0
 * @since   2.0.0 added
 * @since   2.0.1 updated addCallable(), runHandlers()
 */
trait ExtendedLoggerTrait
{
    use GlobbingTrait;

    /**
     * Collection of handler queues
     *
     * @var    array
     * @access protected
     */
    protected $handlers = [];

    /**
     * Collection of processor queues
     *
     * @var    array
     * @access protected
     */
    protected $processors = [];

    /**
     * default channel name
     *
     * @var    string
     * @access protected
     */
    protected $default_channel;

    /**
     * Current channel name
     *
     * @var    string
     * @access protected
     */
    protected $current_channel;

    /**
     * Get current channel, if not set, get the default channel
     *
     * @return string
     * @access protected
     */
    protected function getChannel()/*# : string */
    {
        return $this->current_channel ?: $this->default_channel;
    }

    /**
     * Add callable to the $type queue
     *
     * @param  string $type 'handlers' or 'processors'
     * @param  callable $callable
     * @param  string $channel
     * @param  int $priority  -100 - +100
     * @param  string $level
     * @return $this
     * @access protected
     * @since  2.0.1 added level here
     */
    protected function addCallable(
        /*# string */ $type,
        callable $callable,
        /*# string */ $channel,
        /*# int */ $priority,
        /*# string */ $level = ''
    ) {
        $q = &$this->$type;
        $c = strtoupper($channel);

        if (!isset($q[$c])) {
            $q[$c] = new PriorityQueue();
        }

        /* @var PriorityQueue $queue */
        $queue = $q[$c];
        $queue->insert($callable, $priority, $level);

        return $this;
    }

    /**
     * Remove callable for $type
     *
     * @param  string $type
     * @param  callable|string $callableOrClassname
     * @param  string $channel
     * @return $this
     * @access protected
     */
    protected function removeCallable(
        /*# string */ $type,
        $callableOrClassname,
        /*# string */ $channel
    ) {
        $channels = $channel ? (array) $channel : $this->getAllChannels($type);

        $q = &$this->$type;
        foreach ($channels as $ch) {
            $c = strtoupper($ch);
            /* @var PriorityQueue $queue */
            if (isset($q[$c])) {
                $this->removeFromQueue($q[$c], $callableOrClassname);
            }
        }
        return $this;
    }

    /**
     * Remove callable or matching classname object from the queue
     *
     * @param  PriorityQueueInterface $queue
     * @param  callable|string $callabOrClassname
     * @access protected
     */
    protected function removeFromQueue(
        PriorityQueueInterface $queue,
        $callabOrClassname
    ) {
        if (is_object($callabOrClassname)) {
            $queue->remove($callabOrClassname);
        } else {
            foreach ($queue as $data) {
                if (is_a($data['data'], $callabOrClassname)) {
                    $queue->remove($data['data']);
                }
            }
        }
    }

    /**
     * Get all the channels for handlers or processors
     *
     * @param  string $type 'handlers' or 'processors'
     * @return array
     * @access protected
     */
    protected function getAllChannels(/*# string */ $type)/*# : array */
    {
        return array_keys($this->$type);
    }

    /**
     * Execute related processors on the log entry
     *
     * @param  LogEntryInterface $logEntry
     * @return $this
     * @access protected
     */
    protected function runProcessors(LogEntryInterface $logEntry)
    {
        // get related processors
        $queue = $this->getCallables('processors', $logEntry->getChannel());

        // loop thru these processors
        foreach ($queue as $data) {
            call_user_func($data['data'], $logEntry);
        }

        return $this;
    }

    /**
     * Execute related handlers on the log entry
     *
     * @param  LogEntryInterface $logEntry
     * @return $this
     * @access protected
     * @since  2.0.1 added level checking here
     */
    protected function runHandlers(LogEntryInterface $logEntry)
    {
        // get related handlers
        $queue = $this->getCallables('handlers', $logEntry->getChannel());

        // loop thru these handlers
        foreach ($queue as $data) {
            // stopped ?
            if ($logEntry->isPropagationStopped()) {
                break;
            }

            // run handler only if level allowed
            if (LogLevel::$levels[$logEntry->getLevel()] >=
                LogLevel::$levels[$data['extra']]) {
                call_user_func($data['data'], $logEntry);
            }
        }

        return $this;
    }

    /**
     * Get all matching handlers/processors with channel name globbing
     *
     * @param  string $type
     * @param  string $channel
     * @return PriorityQueue
     * @access protected
     */
    protected function getCallables(
        /*# string */ $type,
        /*# string */ $channel
    )/*# : PriorityQueue */ {

        // name globbing with all channels
        $matchedChannels = $this->globbingNames(
            $channel,
            $this->getAllChannels($type)
        );

        // type queues
        $q = &$this->$type;

        // merge queues
        $queue = new PriorityQueue();
        foreach ($matchedChannels as $c) {
            $queue = $queue->combine($q[$c]);
        }

        return $queue;
    }
}
