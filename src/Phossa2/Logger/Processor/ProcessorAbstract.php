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

use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Logger\Entry\LogEntryInterface;

/**
 * ProcessorAbstract
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     ProcessorInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
abstract class ProcessorAbstract extends ObjectAbstract implements ProcessorInterface
{
    /**
     * {@inheritDoc}
     */
    abstract public function __invoke(LogEntryInterface $logEntry);
}
