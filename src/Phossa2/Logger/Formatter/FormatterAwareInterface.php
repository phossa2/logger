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

/**
 * FormatterAwareInterface
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface FormatterAwareInterface
{
    /**
     * Set formatter
     *
     * @param  callable $formatter
     * @return $this
     * @access public
     */
    public function setFormatter(callable $formatter = null);

    /**
     * Get formatter
     *
     * If not set, return the DefaultFormatter
     *
     * @return callable
     * @access public
     */
    public function getFormatter()/*# : callable */;
}
