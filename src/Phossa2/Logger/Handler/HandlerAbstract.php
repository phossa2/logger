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

use Phossa2\Logger\LogLevel;
use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Logger\Entry\LogEntryInterface;
use Phossa2\Logger\Formatter\FormatterInterface;
use Phossa2\Logger\Formatter\FormatterAwareTrait;
use Phossa2\Logger\Formatter\FormatterAwareInterface;

/**
 * HandlerAbstract
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     HandlerInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
abstract class HandlerAbstract extends ObjectAbstract implements HandlerInterface, FormatterAwareInterface
{
    use FormatterAwareTrait;

    /**
     * @var    string
     * @access protected
     */
    protected $level;

    /**
     * Stop log propagation
     *
     * @var    bool
     * @access protected
     */
    protected $stop;

    /**
     * Created with level handling
     *
     * @param  string $logLevel
     * @param  FormatterInterface $formatter
     * @param  bool $stopPropagation
     * @access protected
     */
    public function __construct(
        /*# string */ $level = LogLevel::WARNING,
        FormatterInterface $formatter = null,
        /*# bool */ $stopPropagation = false
    ) {
        $this->level = $level;
        $this->stop  = (bool) $stopPropagation;
        $this->setFormatter($formatter);
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(LogEntryInterface $logEntry)
    {
        if ($this->isHandling($logEntry->getLevel())) {
            // format message
            ($this->getFormatter())($logEntry);

            // write method
            $this->write($logEntry);

            // stop propagation
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
     * Is this handler handling this level of message ?
     *
     * @param  string $level
     * @return bool
     * @access protected
     */
    protected function isHandling(/*# string */ $level)/*# : bool */
    {
        if (LogLevel::$levels[$level] >= LogLevel::$levels[$this->level]) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Write to the device
     *
     * @param  LogEntryInterface $logEntry
     * @access protected
     */
    abstract protected function write(LogEntryInterface $logEntry);

    /**
     * Close the handler, to be extended by siblings
     *
     * @access protected
     */
    protected function close()
    {
    }
}
