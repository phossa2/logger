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
 * DefaultFormatter
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     FormatterInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class DefaultFormatter extends ObjectAbstract implements FormatterInterface
{
    /**
     * default message format
     *
     * @var    string
     * @access protected
     */
    protected $format = '[%datetime%] %level%: %message%';

    /**
     * {@inheritDoc}
     */
    public function __invoke(LogEntryInterface $logEntry)/*# : string */
    {
        $data = [
            '%datetime%'    => date('Y-m-d H:i:s', $logEntry->getTimestamp()),
            '%level%'       => strtoupper($logEntry->getLevel()),
            '%message%'     => $logEntry->getMessage()
        ];
        return strtr($this->format, $data);
    }
}
