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

namespace Phossa2\Logger\Message;

use Phossa2\Logger\Message\Message;

/*
 * Provide zh_CN translation
 *
 * @package Phossa2\Logger
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
return [
    Message::LOG_LEVEL_INVALID => '未知日志级别名称 "%s"',
    Message::LOG_CHANNEL_NOTSET => '必须每次都设置日志标识',
    Message::LOG_SYSLOG_FAIL => '写入系统日志  "%s:%s" 失败',
    Message::LOG_STREAM_FAIL => '打开日志流媒体 "%s" 失败',
    Message::LOG_STREAM_INVALID => '日志流媒体 "%s" 不对',
];
