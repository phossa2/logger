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

use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Logger\Entry\LogEntryInterface;
use Phossa2\Logger\Formatter\FormatterInterface;
use Phossa2\Logger\Formatter\FormatterAwareTrait;

/**
 * HandlerAbstract
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     HandlerInterface
 * @version 2.0.0
 * @since   2.0.0 added
 * @since   2.0.1 removed level param from constructor
 */
abstract class HandlerAbstract extends ObjectAbstract implements HandlerInterface
{
    use FormatterAwareTrait;

    /**
     * Stop log propagation after this handler ?
     *
     * @var    bool
     * @access protected
     */
    protected $stop;

    /**
     * Created with level handling
     *
     * @param  FormatterInterface $formatter if any
     * @param  bool $stopPropagation if u want to
     * @access public
     * @since  2.0.1 removed level param
     */
    public function __construct(
        FormatterInterface $formatter = null,
        /*# bool */ $stopPropagation = false
    ) {
        $this->stop  = (bool) $stopPropagation;
        $this->setFormatter($formatter);
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(LogEntryInterface $logEntry)
    {
        if ($this->isHandling($logEntry)) {
            // format message with formatter
            call_user_func($this->getFormatter(), $logEntry);

            // write method of this handler
            $this->write($logEntry);

            // stop propagation if u want to
            if ($this->stop) {
                $logEntry->stopPropagation(true);
            }
        }
    }

    /**
     * Destructor
     *
     * @access public
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Write to handler's device
     *
     * @param  LogEntryInterface $logEntry
     * @access protected
     */
    abstract protected function write(LogEntryInterface $logEntry);

    /**
     * Is this handler handling this log ?
     *
     * To be overriden by child classes
     *
     * @param  LogEntryInterface $logEntry
     * @return bool
     * @access protected
     */
    protected function isHandling(LogEntryInterface $logEntry)/*# : bool */
    {
        return true;
    }

    /**
     * Close the handler, to be overriden by child classes
     *
     * @access protected
     */
    protected function close()
    {
    }

    /**
     * Get EOL char base on the platform WIN or UNIX
     *
     * @return string
     * @access protected
     */
    protected function getEol()/*# : string */
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return "\r\n";
        } else {
            return "\n";
        }
    }

    /**
     * Test to see if in CLI mode
     *
     * @return bool
     * @access protected
     */
    protected function isCliMode()/*# : bool */
    {
        return 'cli' === php_sapi_name();
    }
}
