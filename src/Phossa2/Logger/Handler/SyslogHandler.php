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
use Phossa2\Logger\Message\Message;
use Phossa2\Logger\Entry\LogEntryInterface;
use Phossa2\Logger\Exception\RuntimeException;
use Phossa2\Logger\Formatter\FormatterInterface;

/**
 * SyslogHandler
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     HandlerAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 */
class SyslogHandler extends HandlerAbstract
{
    /**
     * syslog facility
     *
     * @var    int
     * @access protected
     */
    protected $facility;

    /**
     * syslog options
     *
     * @var    int
     * @access protected
     */
    protected $logopts;

    /**
     * syslog priorities
     *
     * @var    array
     * @access protected
     */
    protected $priorities = [
        LogLevel::DEBUG     => LOG_DEBUG,
        LogLevel::INFO      => LOG_INFO,
        LogLevel::NOTICE    => LOG_NOTICE,
        LogLevel::WARNING   => LOG_WARNING,
        LogLevel::ERROR     => LOG_ERR,
        LogLevel::CRITICAL  => LOG_CRIT,
        LogLevel::ALERT     => LOG_ALERT,
        LogLevel::EMERGENCY => LOG_EMERG,
    ];

    /**
     * @param  int $facility
     * @param  int $logOpts
     * @param  string $level
     * @param  FormatterInterface $formatter
     * @param  bool $stopPropagation
     * @access public
     */
    public function __construct(
        /*# int */ $facility = LOG_USER,
        /*# int */ $logOpts  = LOG_PID,
        /*# string */ $level = LogLevel::WARNING,
        FormatterInterface $formatter = null,
        /*# bool */ $stopPropagation = false
    ) {
        $this->facility = $facility;
        $this->logopts  = $logOpts;
        parent::__construct($level, $formatter, $stopPropagation);
    }

    /**
     * {@inheritDoc}
     */
    protected function write(LogEntryInterface $logEntry)
    {
        $ident = $logEntry->getChannel();

        if (!openlog($ident, $this->logopts, $this->facility)) {
            throw new RuntimeException(
                Message::get(Message::LOG_SYSLOG_FAIL, $ident, $this->facility),
                Message::LOG_SYSLOG_FAIL
            );
        }


        syslog(
            $this->priorities[$logEntry->getLevel()],
            $logEntry->getFormatted()
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function close()
    {
        closelog();
    }
}
