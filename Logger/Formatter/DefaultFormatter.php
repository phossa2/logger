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
 * DefaultFormatter
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     FormatterAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 */
class DefaultFormatter extends FormatterAbstract
{
    /**
     * default message format
     *
     * @var    string
     * @access protected
     */
    protected $format = '[%datetime%] %channel%.%level%: %message%';

    /**
     * Inject the format if any
     *
     * @param string $format
     * @access protected
     */
    public function __construct(/*# string */ $format = '')
    {
        if ($format) {
            $this->format = $format;
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function format(
        LogEntryInterface $logEntry
    )/*# : string */ {
        $data = [
            '%datetime%'    => date('Y-m-d H:i:s', $logEntry->getTimestamp()),
            '%level%'       => strtoupper($logEntry->getLevel()),
            '%message%'     => $logEntry->getMessage(),
            '%channel%'     => $logEntry->getChannel(),
        ];
        return strtr($this->format, $data);
    }
}
