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
 * @since   2.0.1 updated constructor, isHandling() etc.
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
     *
     * @since 2.0.1 removed level param
     */
    public function __construct(
        FormatterInterface $formatter = null,
        /*# bool */ $stopPropagation = false
    ) {
        // non CLI mode only
        if (!$this->isCliMode()) {
            // register flush method
            register_shutdown_function([__CLASS__, 'flush']);

            // call parent constructor
            parent::__construct($formatter, $stopPropagation);
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function write(LogEntryInterface $logEntry)
    {
        // record all messages
        static::$messages[] = $logEntry->getFormatted();
    }

    /**
     * Only if not in CLI mode
     *
     * {@inheritDoc}
     */
    protected function isHandling(LogEntryInterface $logEntry)/*# : bool */
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
     * Is 'X-BrowserHandler' header set ？
     *
     * @return bool
     * @access protected
     * @static
     */
    protected static function hasHttpHeader()/*# : bool */
    {
        foreach (headers_list() as $header) {
            if (false !== stripos($header, 'x-browserhandler')) {
                return true;
            }
        }
        return false;
    }

    /**
     * Generate the javascript with spooled messages
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
