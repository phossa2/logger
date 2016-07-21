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

namespace Phossa2\Logger;

use Phossa2\Logger\LogLevel;
use Phossa2\Logger\Message\Message;
use Phossa2\Logger\Handler\StreamHandler;
use Phossa2\Logger\Entry\LogEntryInterface;
use Phossa2\Logger\Exception\LogicException;
use Phossa2\Logger\Formatter\FormatterInterface;
use Phossa2\Logger\Formatter\AnsiColorFormatter;

/**
 * TerminalHandler
 *
 * Log to a terminal with or without ANSI color
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     StreamHandler
 * @version 2.0.0
 * @since   2.0.0 added
 */
class TerminalHandler extends StreamHandler
{
    /**
     * Use ANSI color or not
     *
     * @var    bool
     * @access protected
     */
    protected $color;

    /**
     * Constructor
     *
     * @param  string $stream the stream
     * @param  bool $color use ANSI color formatter or not
     * @param  string $logLevel
     * @param  FormatterInterface $formatter
     * @param  bool $stopPropagation
     * @access public
     */
    public function __construct(
        /*# string */ $stream = 'php://stderr',
        /*# bool */ $color = true,
        /*# string */ $level = LogLevel::DEBUG,
        FormatterInterface $formatter = null,
        /*# bool */ $stopPropagation = false
    ) {
        // skip non-CLI mode
        if (!$this->isCliMode()) {
            return;
        }

        // use ansi color
        $this->color = (bool) $color;

        // check stream
        if (!in_array($stream, ['php://stderr', 'php://stdout'])) {
            throw new LogicException(
                Message::get(Message::LOG_STREAM_INVALID, $stream),
                Message::LOG_STREAM_INVALID
            );
        }

        parent::__construct($stream, $level, $formatter, $stopPropagation);
    }

    /**
     * Override default
     *
     * {@inheritDoc}
     */
    protected function setFormatter(FormatterInterface $formatter = null)
    {
        if ($this->color) {
            $this->formatter = new AnsiColorFormatter($formatter);
        } else {
            $this->formatter = $formatter;
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function isHandlingOther(LogEntryInterface $logEntry)/*# : bool */
    {
        return $this->isCliMode();
    }
}