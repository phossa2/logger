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

namespace Phossa2\Logger\Message;

use Phossa2\Shared\Message\Message as BaseMessage;

/**
 * Mesage class for Phossa2\Logger
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa2\Shared\Message\Message
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Message extends BaseMessage
{
    /*
     * Invalid log level "%s"
     */
    const LOG_LEVEL_INVALID = 1607191058;

    /*
     * Must set channel before using logger
     */
    const LOG_CHANNEL_NOTSET = 1607191059;

    /*
     * Syslog failed for "%s:%s"
     */
    const LOG_SYSLOG_FAIL = 1607191060;

    /*
     * Open stream failed for "%s"
     */
    const LOG_STREAM_FAIL = 1607191061;

    /*
     * Invalid stream "%s"
     */
    const LOG_STREAM_INVALID = 1607191062;

    /**
     * {@inheritDoc}
     */
    protected static $messages = [
        self::LOG_LEVEL_INVALID => 'Invalid log level "%s"',
        self::LOG_CHANNEL_NOTSET => 'Must set channel before using logger',
        self::LOG_SYSLOG_FAIL => 'Syslog failed for "%s:%s"',
        self::LOG_STREAM_FAIL => 'Open stream failed for "%s"',
        self::LOG_STREAM_INVALID => 'Invalid stream "%s"',
    ];
}
