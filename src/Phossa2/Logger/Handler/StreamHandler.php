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

use Phossa2\Logger\Message\Message;
use Phossa2\Logger\Entry\LogEntryInterface;
use Phossa2\Logger\Exception\LogicException;
use Phossa2\Logger\Formatter\FormatterInterface;

/**
 * StreamHandler
 *
 * Log to a stream (file, terminal, url etc.)
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     HandlerAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 * @since   2.0.1 updated constructor
 */
class StreamHandler extends HandlerAbstract
{
    /**
     * stream
     *
     * @var    resource
     * @access protected
     */
    protected $stream;

    /**
     * Constructor
     *
     * @param  string|resource $stream the stream
     * @param  FormatterInterface $formatter
     * @param  bool $stopPropagation
     * @access public
     * @since  2.0.1 removed level param
     */
    public function __construct(
        $stream,
        FormatterInterface $formatter = null,
        /*# bool */ $stopPropagation = false
    ) {
        // open stream
        $strm = $this->openStream($stream);

        if (!is_resource($strm)) {
            throw new LogicException(
                Message::get(Message::LOG_STREAM_FAIL, $stream),
                Message::LOG_STREAM_FAIL
            );
        }
        $this->stream = $strm;

        parent::__construct($formatter, $stopPropagation);
    }

    /**
     * {@inheritDoc}
     */
    protected function write(LogEntryInterface $logEntry)
    {
        if ($this->stream) {
            flock($this->stream, LOCK_EX);
            fwrite($this->stream, $logEntry->getFormatted() . $this->getEol());
            flock($this->stream, LOCK_UN);
        }
    }

    /**
     * Open stream for writing
     *
     * @param  string|resource $path
     * @return resource|false
     * @access protected
     */
    protected function openStream(/*# string */ $path)
    {
        if (is_string($path)) {
            if (false !== strpos($path, '://')) {
                $path = 'file://' . $path;
            }
            return fopen($path, 'a');
        }
        return $path;
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        if ($this->stream) {
            fclose($this->stream);
        }
    }
}
