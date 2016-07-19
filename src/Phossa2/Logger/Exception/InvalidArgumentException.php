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

namespace Phossa2\Logger\Exception;

use Psr\Log\InvalidArgumentException as PsrInvalidArgumentException;

/**
 * InvalidArgumentException for Phossa2\Logger
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     ExceptionInterface
 * @see     \Psr\Log\InvalidArgumentException
 * @version 2.0.0
 * @since   2.0.0 added
 */
class InvalidArgumentException extends PsrInvalidArgumentException implements ExceptionInterface
{
}
