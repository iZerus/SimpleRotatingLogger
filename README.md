# SimpleRotateLogger

✨ Monolog, but simpler: Static syntax (`Log::info()`) + automatic log rotation by file size.

Installing:
```shell
composer require izerus/simple-rotating-logger
```

Usage:

```php
use Izerus\SimpleRotatingLogger\Log;
use Izerus\SimpleRotatingLogger\LogBuilder;
use Monolog\Logger;

// Prepare logger
$maxFiles = 9; $maxFileSize = 10485760;
$builder = new LogBuilder(__DIR__ . '/latest.log', Logger::DEBUG, $maxFiles, $maxFileSize);
$builder->setName('myapp');
Log::build($builder);

// Log message
Log::notice('Hello world!'); // myapp.NOTICE: Hello world!

// Get named logger
$logger = Log::getLogger('custom');
$logger->info('Hello world!'); // custom.INFO: Hello world!
```
