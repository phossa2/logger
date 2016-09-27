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

namespace Phossa2\Logger\Handler;

use Phossa2\Logger\Entry\LogEntryInterface;

/**
 * EchoHandler
 *
 * Directly echo the log message
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     HandlerAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 */
class EchoHandler extends HandlerAbstract
{
    /**
     * {@inheritDoc}
     */
    protected function write(LogEntryInterface $logEntry)
    {
        echo $logEntry->getFormatted();
    }
}
