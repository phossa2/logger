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

use Psr\Log\LogLevel as PsrLogLevel;

/**
 * LogLevel
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Psr\Log\LogLevel
 * @version 2.0.0
 * @since   2.0.0 added
 */
class LogLevel extends PsrLogLevel
{
    /**
     * valid levels
     *
     * @var    array
     * @access public
     * @staticvar
     */
    public static $levels = [
        self::DEBUG     => 10,
        self::INFO      => 20,
        self::NOTICE    => 30,
        self::WARNING   => 40,
        self::ERROR     => 50,
        self::CRITICAL  => 60,
        self::ALERT     => 70,
        self::EMERGENCY => 80,
    ];
}
