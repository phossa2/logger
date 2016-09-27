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
use Phossa2\Logger\Exception\LogicException;
use Phossa2\Logger\Formatter\FormatterInterface;

/**
 * LogfileHandler
 *
 * Log to a file with file rotation support
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     StreamHandler
 * @version 2.0.0
 * @since   2.0.0 added
 * @since   2.0.1 updated constructor
 */
class LogfileHandler extends StreamHandler
{
    /**
     * rotation type
     * @var    int
     */
    const ROTATE_NONE =  0; // do not rotate
    const ROTATE_DATE = -1; // rotate by date

    /**
     * Constructor
     *
     * @param  string $path full path
     * @param  int $rotate rotate type or filesize in MB
     * @param  FormatterInterface $formatter
     * @param  bool $stopPropagation
     * @throws LogicException if path not writable
     * @access public
     * @since  2.0.1 removed level param
     */
    public function __construct(
        /*# string */ $path,
        /*# int */ $rotate = self::ROTATE_NONE,
        FormatterInterface $formatter = null,
        /*# bool */ $stopPropagation = false
    ) {
        // remove prefix 'file://' if any
        if ('file://' === substr($path, 0, 7)) {
            $path = substr($path, 7);
        }

        // check file path
        $this->checkPath($path);

        // rotate file ?
        if (file_exists($path)) {
            $this->doRotation($path, $rotate);
        }

        parent::__construct($path, $formatter, $stopPropagation);
    }

    /**
     * Check file path
     *
     * @param  string $path
     * @throws LogicException if directory failure etc.
     * @access protected
     */
    protected function checkPath(/*# string */$path)
    {
        // get the directory
        $dir = dirname(realpath($path));

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (!is_dir($dir) || !is_writable($dir)) {
            throw new LogicException(
                Message::get(Message::MSG_PATH_NONWRITABLE, $dir),
                Message::MSG_PATH_NONWRITABLE
            );
        }
    }

    /**
     * Rotate file on start
     *
     * @param  string $path
     * @param  int $type
     * @return bool rotation status
     * @access protected
     */
    protected function doRotation(
        /*# string */ $path,
        /*# int */ $type
    )/*# : bool */ {
        switch ($type) {
            // no rotation
            case self::ROTATE_NONE:
                return true;

            // rotate by date
            case self::ROTATE_DATE:
                return $this->rotateByDate($path);

            // rotate by size
            default:
                return $this->rotateBySize($path, $type);
        }
    }

    /**
     * Rotate $path to $path_20160616
     *
     * @param  string $path
     * @param  string $format date format
     * @return bool rotation status
     * @access protected
     */
    protected function rotateByDate(
        /*# string */ $path,
        /*# string */ $format = 'Ymd'
    )/*# : bool */ {
        $time = filectime($path);
        return rename($path, $path . '_' . date($format, $time));
    }

    /**
     * Rotate $path if filesize > the specified in MB
     *
     * Rotate to $path.201606141310 (hour & minute)
     *
     * @param  string $path
     * @param  int $size size in MB
     * @return bool rotation status
     * @access protected
     */
    protected function rotateBySize(
        /*# string */ $path,
        /*# int */ $size
    )/*# : bool */ {
        if (round(filesize($path) / (1024 * 1024), 2) > $size) {
            return $this->rotateByDate($path, 'YmdHi');
        }
        return true;
    }
}
