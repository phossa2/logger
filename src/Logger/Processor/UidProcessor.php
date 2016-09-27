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
 * UidProcessor
 *
 * Inject an unique id for this session. Should be the first processor ?
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @see     ProcessorAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 */
class UidProcessor extends ProcessorAbstract
{
    /**
     * @var    string
     * @access protected
     */
    protected $uid;

    /**
     * Using external uid or create one
     *
     * @param  string $uid external one
     * @param  int $length uid length
     * @access public
     */
    public function __construct(/*# string */ $uid = '', /*# int */ $length = 8)
    {
        if (empty($uid)) {
            $uid = substr(hash('md5', uniqid('', true)), 0, $length);
        }
        $this->uid = $uid;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(LogEntryInterface $logEntry)
    {
        $context = $logEntry->getContext();
        $context['uid'] = $this->uid;
        $logEntry->setContext($context);
    }
}
