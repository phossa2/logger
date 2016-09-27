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

use Psr\Log\LogLevel as PsrLogLevel;
use Phossa2\Logger\Exception\InvalidArgumentException;

/**
 * LogEntryInterface
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface LogEntryInterface
{
    /**
     * Set log channel
     *
     * @param  string $channel
     * @return $this
     * @access public
     * @api
     */
    public function setChannel(
        /*# string */ $channel
    );

    /**
     * Get log channel
     *
     * @return string
     * @access public
     * @api
     */
    public function getChannel()/*# : string */;

    /**
     * Set log message
     *
     * @param  string $message the original message string
     * @return $this
     * @access public
     * @api
     */
    public function setMessage(
        /*# string */ $message
    );

    /**
     * Get log message
     *
     * @return string
     * @access public
     * @api
     */
    public function getMessage()/*# : string */;

    /**
     * Set log level
     *
     * @param  string $level string constant
     * @return $this
     * @access public
     * @throws InvalidArgumentException if invalid level given
     * @api
     */
    public function setLevel(
        /*# string */ $level = PsrLogLevel::INFO
    );

    /**
     * Get message level
     *
     * @return string
     * @access public
     * @api
     */
    public function getLevel()/*# : string */;

    /**
     * Set the formatted message
     *
     * @param  string $formatted
     * @return $this
     * @access public
     * @api
     */
    public function setFormatted(/*# string */ $formatted);

    /**
     * Get the formatted message.
     *
     * If not formatted yet, will format with 'DefaultFormatter'
     *
     * @return string
     * @access public
     * @api
     */
    public function getFormatted()/*# string */;

    /**
     * Set timestamp, default is current UNIX timestamp in float
     *
     * @param  float $timestamp UNIX time in float, 0 for now
     * @return $this
     * @access public
     * @api
     */
    public function setTimestamp(
        /*# : float */ $timestamp = 0
    );

    /**
     * Get UNIX timestamp
     *
     * @return float
     * @access public
     * @api
     */
    public function getTimestamp()/*# : float */;

    /**
     * Set log related context
     *
     * @param  array $context
     * @return $this
     * @access public
     * @api
     */
    public function setContext(array $context);

    /**
     * Get log context array
     *
     * @return array
     * @access public
     * @api
     */
    public function getContext()/*# : array */;

    /**
     * Indicate whether or not to stop propagating this log
     *
     * @param  bool $flag
     * @return $this
     * @access public
     * @api
     */
    public function stopPropagation(/*# : bool */ $flag = true);

    /**
     * Has this log indicated propagation should stop?
     *
     * @return bool
     * @access public
     * @api
     */
    public function isPropagationStopped()/*# : bool */;

    /**
     * To string
     *
     * @return string
     * @access public
     * @api
     */
    public function __toString()/*# : string */;
}
