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

namespace Phossa2\Logger\Processor;

use Phossa2\Logger\Entry\LogEntryInterface;

/**
 * InterpolateProcessor
 *
 * Replace '{placeholder}' with values from context in the log message.
 * Should be the last processor in the queue before log handling.
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     ProcessorAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 */
class InterpolateProcessor extends ProcessorAbstract
{
    /**
     * Replace any '{item}' in the messsage with context['item'] value
     *
     * @see http://www.php-fig.org/psr/psr-3/
     *
     * {@inheritDoc}
     */
    public function __invoke(LogEntryInterface $logEntry)
    {
        $message = $logEntry->getMessage();
        $context = $logEntry->getContext();

        $replace = [];
        foreach ($this->getPlaceHolders($message) as $name => $ph) {
            $replace[$ph] = $this->replaceWith($name, $ph, $context);
        }

        $logEntry->setMessage(strtr($message, $replace));
    }

    /**
     * Get placeholders in array
     *
     * @param  string $message
     * @return array
     * @access protected
     */
    protected function getPlaceHolders(/*# string */ $message)/*# : array */
    {
        // not found
        if (false === strpos($message, '{')) {
            return [];
        }

        $matches = [];
        $pattern = '~\{([^\}]+)\}~';
        if (preg_match_all($pattern, $message, $matches)) {
            return array_combine($matches[1], $matches[0]);
        }
        return [];
    }

    /**
     * Replace with values from the context array
     *
     * @param  string $name
     * @param  string $placeholder
     * @param  array &$context
     * @return string
     * @access protected
     */
    protected function replaceWith(
        /*# string */ $name,
        /*# string */ $placeholder,
        array &$context
    )/*# : string */ {
        // exact match
        if (isset($context[$name])) {
            return $this->getString($context[$name]);
        }

        // something like user.name
        $first = explode('.', $name)[0];
        if (false !== strpos($name, '.') && isset($context[$first])) {
            return $this->getSubPart($name, $placeholder, $context[$first]);
        }

        // not found
        return $placeholder;
    }

    /**
     * Get string representation of mixed-typed $data
     *
     * @param  mixed $data
     * @return string
     * @access protected
     */
    protected function getString($data)/*# : string */
    {
        if (is_scalar($data)) {
            return strval($data);
        } elseif (is_array($data)) {
            return 'ARRAY[' . count($data) . ']';
        } elseif (is_object($data)) {
            return $this->getObjectString($data);
        } else {
            return 'TYPE: ' . gettype($data);
        }
    }

    /**
     * Get string representation of an object
     *
     * @param  object $object
     * @return string
     * @access protected
     */
    protected function getObjectString($object)/*# : string */
    {
        // exception found
        if ($object instanceof \Exception) {
            return 'EXCEPTION: ' . $object->getMessage();

        // toString() found
        } elseif (method_exists($object, '__toString')) {
            return (string) $object;

        // other type object
        } else {
            return 'OBJECT: ' . get_class($object);
        }
    }

    /**
     * Get 'user.name' type of result, only support 2 level
     *
     * @param  string $name
     * @param  string $placeholder used only if nothing matched
     * @param  mixed $data
     * @return string
     * @access protected
     */
    protected function getSubPart(
        /*# string */ $name,
        /*# string */ $placeholder,
        $data
    )/*# : string */ {
        list(, $second) = explode('.', $name, 2);

        $arr = (array) $data;
        if (isset($arr[$second])) {
            return $arr[$second];
        }

        return $placeholder;
    }
}
