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
 * FormatterAwareTrait
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     FormatterAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait FormatterAwareTrait
{
    /**
     * formatter
     *
     * @var    callable
     * @access protected
     */
    protected $formatter;

    /**
     * {@inheritDoc}
     */
    public function setFormatter(callable $formatter = null)
    {
        $this->formatter = $formatter;
    }

    /**
     * {@inheritDoc}
     */
    public function getFormatter()/*# : callable */
    {
        if (is_null($this->formatter)) {
            $this->formatter = new DefaultFormatter();
        }
        return $this->formatter;
    }
}
