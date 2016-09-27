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

use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Logger\Entry\LogEntryInterface;

/**
 * FormatterAbstract
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     FormatterInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
abstract class FormatterAbstract extends ObjectAbstract implements FormatterInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(LogEntryInterface $logEntry)
    {
        $logEntry->setFormatted($this->format($logEntry));
    }

    /**
     * Returns the formatted message
     *
     * @param  LogEntryInterface $logEntry
     * @return string
     * @access protected
     */
    abstract protected function format(
        LogEntryInterface $logEntry
    )/*# : string */;
}
