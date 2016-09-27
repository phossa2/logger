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

use Phossa2\Logger\LogLevel;
use Phossa2\Logger\Entry\LogEntryInterface;

/**
 * AnsiColorFormatter
 *
 * Adding ANSI color base on the log level to the message after it is
 * formatted by a slave formatter. This formatter can be used with the
 * 'TerminalHandler'
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     FormatterAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 */
class AnsiColorFormatter extends FormatterAbstract
{
    /**
     * foreground color
     *
     * @const
     */
    const FGCOLOR_BLACK          = "\033[0;30m";
    const FGCOLOR_RED            = "\033[0;31m";
    const FGCOLOR_GREEN          = "\033[0;32m";
    const FGCOLOR_YELLOW         = "\033[0;33m";
    const FGCOLOR_BLUE           = "\033[0;34m";
    const FGCOLOR_MAGENTA        = "\033[0;35m";
    const FGCOLOR_CYAN           = "\033[0;36m";
    const FGCOLOR_GRAY           = "\033[0;37m";
    const FGCOLOR_DARK_GRAY      = "\033[1;30m";
    const FGCOLOR_BRIGHT_RED     = "\033[1;31m";
    const FGCOLOR_BRIGHT_GREEN   = "\033[1;32m";
    const FGCOLOR_BRIGHT_YELLOW  = "\033[1;33m";
    const FGCOLOR_BRIGHT_BLUE    = "\033[1;34m";
    const FGCOLOR_BRIGHT_MAGENTA = "\033[1;35m";
    const FGCOLOR_BRIGHT_CYAN    = "\033[1;36m";
    const FGCOLOR_WHITE          = "\033[1;37m";

    /**
     * background color
     *
     * @const
     */
    const BGCOLOR_BLACK          = "\033[40m";
    const BGCOLOR_RED            = "\033[41m";
    const BGCOLOR_GREEN          = "\033[42m";
    const BGCOLOR_YELLOW         = "\033[43m";
    const BGCOLOR_BLUE           = "\033[44m";
    const BGCOLOR_MAGENTA        = "\033[45m";
    const BGCOLOR_CYAN           = "\033[46m";
    const BGCOLOR_WHITE          = "\033[47m";
    const DECO_BOLD              = "\033[1m";
    const DECO_UNDERLINE         = "\033[4m";
    const DECO_BLINK             = "\033[5m";
    const DECO_REVERSE           = "\033[7m";
    const DECO_CROSS             = "\033[9m";
    const DECO_END               = "\033[0m";

    /**
     * Color definitions for different log levels
     *
     * format  [ fgColor, bgColor, textDeco ]
     *
     * @var     array
     * @access  protected
     */
    protected $colors = array(
        LogLevel::DEBUG     => [self::FGCOLOR_GRAY, '', ''],
        LogLevel::INFO      => ['', '', ''],
        LogLevel::NOTICE    => [self::FGCOLOR_BRIGHT_GREEN, '', ''],
        LogLevel::WARNING   => [self::FGCOLOR_BRIGHT_YELLOW, '', ''],
        LogLevel::ERROR     => [self::FGCOLOR_BRIGHT_RED, '', ''],
        LogLevel::CRITICAL  => [self::FGCOLOR_BRIGHT_RED, '', self::DECO_UNDERLINE],
        LogLevel::ALERT     => [self::FGCOLOR_BRIGHT_RED, self::BGCOLOR_WHITE, ''],
        LogLevel::EMERGENCY => [self::FGCOLOR_BRIGHT_RED, self::BGCOLOR_WHITE, self::DECO_BLINK],
    );

    /**
     * Slave formatter
     *
     * @var    FormatterInterface
     * @access protected
     */
    protected $slave;

    /**
     * Constructor
     * @param  FormatterInterface $formatter slave formatter
     * @access public
     */
    public function __construct(FormatterInterface $formatter = null)
    {
        $this->setSlave($formatter);
    }

    /**
     * Set slave formatter
     *
     * @param  FormatterInterface $formatter the normal formatter
     * @access public
     * @api
     */
    public function setSlave(FormatterInterface $formatter = null)
    {
        $this->slave = $formatter;
    }

    /**
     * {@inheritDoc}
     */
    protected function format(
        LogEntryInterface $logEntry
    )/*# : string */ {
        // set default slave
        if (is_null($this->slave)) {
            $this->setSlave(new DefaultFormatter());
        }

        // format with slave first
        call_user_func($this->slave, $logEntry);

        // add colors
        return $this->addColor(
            $logEntry->getFormatted(),
            $this->colors[$logEntry->getLevel()]
        );
    }

    /**
     * add ansi color to text
     *
     * @param  string $text text to color
     * @param  array $definition coloring definition
     * @return string
     * @access protected
     */
    protected function addColor(
        /*# string */ $text,
        array $definition
    )/*# : string */ {
        $fgColor = $definition[0];
        $bgColor = $definition[1];
        $deColor = $definition[2];
        $prefix  = $fgColor . $bgColor . $deColor;
        $suffix  = $prefix ? self::DECO_END : '';
        return $prefix . $text . $suffix;
    }
}
