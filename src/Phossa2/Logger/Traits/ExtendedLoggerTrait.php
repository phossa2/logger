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

use Phossa2\Logger\Message\Message;
use Phossa2\Shared\Queue\PriorityQueue;
use Phossa2\Shared\Globbing\GlobbingTrait;
use Phossa2\Logger\Entry\LogEntryInterface;
use Phossa2\Logger\Exception\RuntimeException;
use Phossa2\Logger\Handler\HandlerInterface;

/**
 * ExtendedLoggerTrait
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     interface
 * @version 2.0.0
 * @since   2.0.0 added
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
     * channel name
     *
     * @var    string
     * @access protected
     */
    protected $channel = 'LOGGER';

    /**
     * marker for set channel
     *
     * @var    bool
     * @access protected
     */
    protected $channel_set = false;

    /**
     * Set channel for this log
     *
     * @param  string $channel
     * @return $this
     * @access protected
     */
    protected function setChannel(/*# string */ $channel)
    {
        $this->channel_set = true;
        $this->channel = strtoupper($channel);
        return $this;
    }

    /**
     * Get logger channel
     *
     * @return string
     * @access protected
     */
    protected function getChannel()/*# : string */
    {
        return $this->channel;
    }

    /**
     * Add callable to the $type queue
     *
     * @param  string $type 'handlers' or 'processors'
     * @param  callable $callable
     * @param  string $channel
     * @param  int $priority  -100 - +100
     * @return $this
     * @access protected
     */
    protected function addCallable(
        /*# string */ $type,
        callable $callable,
        /*# string */ $channel,
        /*# int */ $priority
    ) {
        // use current $logger channel
        if (empty($channel)) {
            $channel = $this->getChannel();
        }

        $q = &$this->$type;
        if (!isset($q[$channel])) {
            $q[$channel] = new PriorityQueue();
        }

        /* @var PriorityQueue $queue */
        $queue = $q[$channel];
        $queue->insert($callable, $priority);

        return $this;
    }

    /**
     * Remove callable for $type
     *
     * @param  string $type
     * @param  callable $callable
     * @param  string $channel
     * @return $this
     * @access protected
     */
    protected function removeCallable(
        /*# string */ $type,
        callable $callable,
        /*# string */ $channel
    ) {
        $channels = $channel ? (array) $channel : $this->getAllChannels($type);

        $q = &$this->$type;
        foreach ($channels as $c) {
            /* @var PriorityQueue $queue */
            if (isset($q[$c])) {
                $queue = $q[$c];
                $queue->remove($callable);
            }
        }
        return $this;
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
        foreach($queue as $data) {
            ($data['data'])($logEntry);
        }

        return $this;
    }

    /**
     * Execute related handlers on the log entry
     *
     * @param  LogEntryInterface $logEntry
     * @return $this
     * @access protected
     */
    protected function runHandlers(LogEntryInterface $logEntry)
    {
        // get related handlers
        $queue = $this->getCallables('handlers', $logEntry->getChannel());

        // loop thru these handlers
        foreach($queue as $data) {
            // stopped ?
            if ($logEntry->isPropagationStopped()) {
                break;
            }

            // run handler
            ($data['data'])($logEntry);
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
            $channel, $this->getAllChannels($type)
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

    /**
     * Check if channel is set with $logger($channel)
     *
     * @throws RuntimeException
     * @access protected
     */
    protected function isChannelSet()
    {
        if (!$this->channel_set) {
            throw new RuntimeException(
                Message::get(Message::LOG_CHANNEL_NOTSET),
                Message::LOG_CHANNEL_NOTSET
            );
        }
    }

    /**
     * Mark channel is unset
     *
     * @access protected
     */
    protected function unsetChannel()
    {
        $this->channel_set = false;
    }
}
