# SimpleRotateLogger

✨ Monolog, but simpler: Static syntax (`Log::info()`) + automatic log rotation by file size.

```php
use Izerus\SimpleRotatingLogger\Log;
use Izerus\SimpleRotatingLogger\LogBuilder;
use Monolog\Logger;

$builder = new LogBuilder(__DIR__ . '/latest.log', Logger::DEBUG);
$builder
    ->addFileHandler(__DIR__ . '/error.log', Logger::ERROR)
    ->addStdoutHandler(Logger::NOTICE)
    ->setName('application');
Log::build($builder);

Log::notice('Hello world!');
```
