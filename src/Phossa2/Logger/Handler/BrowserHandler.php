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
        /*# string */ $level = LogLevel::DEBUG,
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
        }
        static::$messages = [];
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
        foreach (static::$messages as $msg) {
            $script[] = 'c.log(' . $msg . ');';
        }
        return "(function (c) {if (c && c.groupCollapsed) {\n" .
            implode("\n", $script) . "\n}})(console);";
    }
}
