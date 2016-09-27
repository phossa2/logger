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

namespace Phossa2\Logger\Formatter;

use Phossa2\Logger\Entry\LogEntryInterface;

/**
 * FormatterInterface
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface FormatterInterface
{
    /**
     * Format a log entry
     *
     * @param  LogEntryInterface $logEntry the log entry
     * @access public
     * @api
     */
    public function __invoke(LogEntryInterface $logEntry);
}
