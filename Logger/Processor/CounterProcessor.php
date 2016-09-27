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
 * CounterProcessor
 *
 * Store a counter in context. Mostly for testing purpose.
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     ProcessorAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 */
class CounterProcessor extends ProcessorAbstract
{
    /**
     * @var    int
     * @access protected
     */
    protected static $counter = 0;

    /**
     * {@inheritDoc}
     */
    public function __invoke(LogEntryInterface $logEntry)
    {
        $context = $logEntry->getContext();
        $context['counter'] = ++static::$counter;
        $logEntry->setContext($context);
    }
}
