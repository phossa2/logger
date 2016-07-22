# phossa2/logger
[![Build Status](https://travis-ci.org/phossa2/logger.svg?branch=master)](https://travis-ci.org/phossa2/logger)
[![Code Quality](https://scrutinizer-ci.com/g/phossa2/logger/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phossa2/logger/)
[![PHP 7 ready](http://php7ready.timesplinter.ch/phossa2/logger/master/badge.svg)](https://travis-ci.org/phossa2/logger)
[![HHVM](https://img.shields.io/hhvm/phossa2/logger.svg?style=flat)](http://hhvm.h4cc.de/package/phossa2/logger)
[![Latest Stable Version](https://img.shields.io/packagist/vpre/phossa2/logger.svg?style=flat)](https://packagist.org/packages/phossa2/logger)
[![License](https://poser.pugx.org/phossa2/logger/license)](http://mit-license.org/)

**phossa2/logger** is a [PSR-3][PSR-3] compliant logging library. It is a
rewrite of Monolog with couple of changes.

It requires PHP 5.4, supports PHP 7.0+ and HHVM. It is compliant with
[PSR-1][PSR-1], [PSR-2][PSR-2], [PSR-3][PSR-3], [PSR-4][PSR-4], and the proposed
[PSR-5][PSR-5].

[PSR-1]: http://www.php-fig.org/psr/psr-1/ "PSR-1: Basic Coding Standard"
[PSR-2]: http://www.php-fig.org/psr/psr-2/ "PSR-2: Coding Style Guide"
[PSR-3]: http://www.php-fig.org/psr/psr-3/ "PSR-3: Logger Interface"
[PSR-4]: http://www.php-fig.org/psr/psr-4/ "PSR-4: Autoloader"
[PSR-5]: https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md "PSR-5: PHPDoc"

Installation
---
Install via the `composer` utility.

```
composer require "phossa2/logger=2.*"
```

or add the following lines to your `composer.json`

```json
{
    "require": {
       "phossa2/logger": "^2.0.0"
    }
}
```

Usage
---

Create the logger instance with default channel,

```php
use Phossa2\Logger\Logger;
use Phossa2\Logger\Handler\SyslogHandler;
use Phossa2\Logger\Handler\LogfileHandler;
use Phossa2\Logger\Processor\MemoryProcessor;
use Phossa2\Logger\Processor\InterpolateProcessor;

// with default channel
$logger = new Logger('MyApp');

// attach memory processor
$logger->addProcessor(new MemoryProcessor);

// attach interpolate processor to all channels' ('*') end (-100)
$logger->addProcessor(new InterpolateProcessor(), '*', -100);

// attach syslog handler to user related channels
$logger->addHandler(new SyslogHandler(), 'user.*');

// attach file handler to all channels
$logger->addHandler(new LogfileHandler('/tmp/app.log', 'warning'));

// log to system.usage channel
$logger
    ->with('system.usage')
    ->debug('memory used {memory.used} and peak is {memory.peak}');

// log to user.login channel
$logger
    ->with('user.login')
    ->info('user logged in as {user.username}', ['user' => $user]);

// log to default channel
$logger->debug('a test message');
```

Features
---

- <a name="channel"></a>**Channels**

  Creative usage of channels. `Handler` and `Processor` now can be bound to
  different channels, also with channel name globbing.

  - *Channel globbing*

    By default, handlers and processors are bound to `'*'` channel which globs
    to all. But they also can be bound to channels like `'user.*'` or more
    specific one `'user.login'`.

    ```php
    // bind to 'user.*' channels
    $logger->addHandler(new LogfileHandler('/log/user.log', 'warning'), 'user.*');

    // bind to 'system.*' channels
    $logger->addHandler(new LogfileHandler('/log/system.log', 'error'), 'system.*');

    // add user info only in 'user.*' channel
    $logger->addProcessor(new UserProcessor(), 'user.*');
    ```

    log messages can be sent to specific channels by using of `with()` in front
    of any logging related methods, such as `log()`, `warning()` etc.

    ```php
    $logger->with('user.login')->info('user {user.username} logged info');
    ```

    The `info()` method in the previous code will trigger user info being
    inserted into context array by the `UserProcessor` and being logged to file
    `/log/user.log`.

    **Note**: Channel names are *case insensitive*.

    **Note**: Same handler or processor can be bound to different channels. But
    will be executed only *ONCE* in one log call.

  - *Single logger*

    With the support for logging to different channels, there is no need to
    create multiple loggers in one application. By carefully designing your
    channel hierachy, you may use one logger through out your site.

- <a name="priority"></a>**Priority**

  Handlers and processors are now can injected into the logger with different
  priorities (range from `-100` to `100`, default is `0`).

  - Higher priority means executed first

    ```php
    // add user info at first
    $logger->addProcessor(new UserProcessor(), 'user.*', 100);

    // interpolate should be done last (just before executing handlers)
    $logger->addProcessor(new InterpolateProcessor(), '*', -100);
    ```

  - First in first out(executed) for same priority

    Default priority value is `0`. The following handlers executed in the
    order of their addition.

    ```php
    // log to file first
    $logger->addHandler(new LogfileHandler('/log/log.txt'));

    // then log to mail
    $logger->addHandler(new MailHandler('admin@my.com'));
    ```

- <a name="callable"></a>**Simple callable interface**

  Handlers, formatters, processors are now all using the single interface

  ```php
  public function __invoke(LogEntryInterface $logEntry);
  ```

  Which means, user may use predefined functions or other callables to servce
  as handler, formatter or processor, as long as these callables take the
  `LogEntryInterface` as the parameter.

  A quick handler as follows,

  ```php
  function myHandler(LogEntryInterface $logEntry) {
      // get formatted message
      $formatted = $logEntry->getFormatted();

      // write to my device ...
  }
  ```

- <a name="entry"></a>**LogEntry**

  In stead of using array as data type for the log message. The
  `LogEntryInterface` is defined to serve as default data type for logs.

  You may even extend the `LogEntry` class, and use it in your logger

  ```php
  class MyLogEntry extends LogEntry
  {
      // ...
  }
  ```

  Use it in your logger as the prototype for all log messages,

  ```php
  $entryPrototype = new MyLogEntry('channle','debug', 'message');

  $logger = new Logger('MyApp', $entryPrototype);
  ```

APIs
---

- <a name="loggerInterface"></a>`LoggerInterface` related

  See [PSR-3][PSR-3] for standard related APIs.

- <a name="logger"></a>`Phossa2\Logger\Logger` related

  - `__construct(string $defaultChannel = 'LOGGER', LogEntryInterface $logPrototype = null)`

    Create the logger.

  - `with(string $channel): $this`

    Specify the channel for the comming logging method.

  - `addHandler(callable $handler, string $channel = '*', int $priority = 0): $this`

    Add one handler to specified channel with the priority.

  - `addProcessor(callable $processor, string $channel = '*', int $priority = 0): $this`

    Add one processor to specified channel with the priority.

  - `removeHandler(callable|string $handlerOrClassname, $channel = '')`

    Remove the handler (or specify handler's classname) from the specified
    channel. If `$channel` is empty, then remove from all channels.

  - `removeProcessor(callable|string $processorOrClassname, $channel = '')`

    Remove the processor (or specify processor's classname) from the specified
    channel. If `$channel` is empty, then remove from all channels.

Dependencies
---

- PHP >= 5.4.0

- phossa2/shared >= 2.0.20

License
---

[MIT License](http://mit-license.org/)
