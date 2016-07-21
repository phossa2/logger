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
use Phossa2\Logger\Entry\LogEntryInterface;
use Phossa2\Logger\Formatter\DefaultFormatter;
use Phossa2\Logger\Formatter\FormatterInterface;

/**
 * BrowserHandler
 *
 * Send logs to browser console.
 *
 * - User MUST add HTTP header 'browerhandler' to html page to works with
 *   this handler.
 *
 * - Modified from Monolog Handler\BrowserConsoleHandler
 *
 * - Message like '[[text goes here]]{background-color: green; color: white}'
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     HandlerAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 */
class BrowserHandler extends HandlerAbstract
{
    /**
     * cached messages
     *
     * @static
     * @var    string[]
     * @access protected
     */
    protected static $messages = [];

    /**
     * {@inheritDoc}
     */
    public function __construct(
        /*# string */ $level = LogLevel::NOTICE,
        FormatterInterface $formatter = null,
        /*# bool */ $stopPropagation = false
    ) {
        // skip CLI mode
        if ($this->isCliMode()) {
            return;
        }

        // register flush
        register_shutdown_function([__CLASS__, 'flush']);

        parent::__construct($level, $formatter, $stopPropagation);
    }

    /**
     * {@inheritDoc}
     */
    protected function write(LogEntryInterface $logEntry)
    {
        static::$messages[] = $logEntry->getFormatted();
    }

    /**
     * Only use this handler in browser mode
     *
     * {@inheritDoc}
     */
    protected function isHandlingOther(LogEntryInterface $logEntry)/*# : bool */
    {
        return !$this->isCliMode();
    }

    /**
     * flush the messages to browser by adding to HTML page
     *
     * @return void
     * @access public
     * @static
     * @api
     */
    public static function flush()
    {
        if (static::hasHttpHeader() && count(static::$messages)) {
            echo '<script>' , static::generateScript() , '</script>';
            static::$messages = [];
        }
    }

    /**
     * Overrided
     *
     * Format your message like
     *
     *  [[text goes here]]{background-color: green; color: white}
     *
     * {@inheritDoc}
     */
    public function getFormatter()/*# : callable */
    {
        if (is_null($this->formatter)) {
            $this->formatter = new DefaultFormatter(
                '[[%channel%]]{macro: autolabel} [[%level_name%]]{font-weight: bold} %message%'
            );
        }
        return $this->formatter;
    }

    /**
     * Is 'browserhandler' header set ？
     *
     * @return bool
     * @access protected
     * @static
     */
    protected static function hasHttpHeader()/*# : bool */
    {
        foreach (headers_list() as $header) {
            if (false !== stripos($header, 'browserhandler')) {
                return true;
            }
        }
        return false;
    }

    /**
     * Generate the javascript
     *
     * @return string
     * @access protected
     */
    protected static function generateScript()/*# : string */
    {
        $script = array();
        foreach (static::$messages as $record) {
            $script[] = static::consoleLog(static::handleStyles($record));
        }
        return
            "(function (c) {if (c && c.groupCollapsed) {\n" .
            implode("\n", $script) .
            "\n}})(console);";
    }

    /**
     * JS log method
     *
     * @param  array $args
     * @return string
     * @access protected
     */
    protected static function consoleLog(array $args)/*# : string */
    {
        return 'c.log(' . implode(', ', $args) . ');';
    }

    /**
     * Format with styles in the message
     *
     * @param  string $formatted
     * @return array
     * @access protected
     */
    protected static function handleStyles(
        /*# : string */ $formatted
    )/*# : array */ {
        $args = array(static::quote('font-weight: normal'));
        $format = '%c' . $formatted;
        preg_match_all(
            '/\[\[(.*?)\]\]\{([^}]*)\}/s',
            $format,
            $matches,
            PREG_OFFSET_CAPTURE | PREG_SET_ORDER
        );
        foreach (array_reverse($matches) as $match) {
            $args[] = static::quote(
                static::handleCustomStyles($match[2][0], $match[1][0])
            );
            $args[] = '"font-weight: normal"';
            $pos = $match[0][1];
            $format =
                substr($format, 0, $pos) . '%c' . $match[1][0] .
                '%c' . substr($format, $pos + strlen($match[0][0]));
        }
        array_unshift($args, static::quote($format));

        return $args;
    }

    /**
     * @param  string $style
     * @param  string $string
     * @return string
     * @access protected
     */
    protected static function handleCustomStyles(
        /*# string */ $style,
        /*# string */ $string
    )/*# : string */ {
        static $colors = array(
            'blue', 'green', 'red', 'magenta', 'orange', 'black', 'grey'
        );
        static $labels = array();

        return preg_replace_callback(
            '/macro\s*:(.*?)(?:;|$)/',
            function ($mstr) use ($string, &$colors, &$labels) {
                if (trim($mstr[1]) === 'autolabel') {
                    // Format the string as a label with consistent
                    // auto assigned background color
                    if (!isset($labels[$string])) {
                        $labels[$string] = $colors[count($labels) % count($colors)];
                    }
                    $color = $labels[$string];
                    return "background-color: $color; color: white; border-radius: 3px; padding: 0 2px 0 2px";
                }
                return $mstr[1];
            },
            $style
        );
    }

    /**
     * Quote $arg
     *
     * @param  string $arg
     * @return string
     * @access protected
     */
    protected static function quote(/*# string */ $arg)/*# : string */
    {
        return '"' . addcslashes($arg, "\"\n\\") . '"';
    }
}
