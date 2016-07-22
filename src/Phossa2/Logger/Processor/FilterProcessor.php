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

namespace Phossa2\Logger\Processor;

use Phossa2\Logger\Entry\LogEntryInterface;

/**
 * FilterProcessor
 *
 * Filtering the log message by some criatory and stop it even before sending
 * to the handlers
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     ProcessorAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 */
class FilterProcessor extends ProcessorAbstract
{
    /**
     * A filter
     *
     * @var    callable
     * @access protected
     */
    protected $filter;

    /**
     * Inject a filtering callable which takes $logEntry as input
     *
     * @param  callable $filter
     * @access public
     */
    public function __construct(callable $filter = null)
    {
        $this->filter = $filter;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(LogEntryInterface $logEntry)
    {
        if (null !== $this->filter &&
            !call_user_func($this->filter, $logEntry)
        ) {
            $logEntry->stopPropagation(true);
        }
    }
}
