
<?php

class Logger {

    public function log($content) 
    {
        //Logs content to file.
        echo "Log to file";
    }
}

class LogController extends Controller
{
    public function log()
    {
        $logger = new Logger;
        $logger->log('Log this');
    }
}


class Logger {

    public function logToDb($content) 
    {
        //Logs content to db.
    }

    public function logToFile($content) 
    {
        //Logs content to file.
    }

    public function logToCloud($content) 
    {
        //Logs content to cloud.
    }
    
}
class LogController extends Controller
{
    public function log()
    {
        $logger = new Logger;

        $target = config('log.target');

        if ($target == 'db') {
            $logger->logToDb($content);
        } elseif ($target == 'file') {
            $logger->logToFile($content);
        } else {
            $logger->logToCloud($content);
        }
    }
}

class DBLogger
{
    public function log()
    {
        //log to db
    }
}

class FileLogger
{
    public function log()
    {
        //log to file
    }
}
class CloudLogger
{
    public function log()
    {
        //log to cloud
    }
}


And the Controller is changed to

class LogController extends Controller
{
    public function log()
    {
        $target = config('log.target');

        if ($target == 'db') {
            (new DBLogger)->log($content);
        } elseif ($target == 'file') {
            (new FileLogger)->log($content);
        } else {
            (new CloudLogger)->log($content);
        }
    }
}

interface Logger
{
    public function log($content);
}


class LogController extends Controller
{
    public function log(Logger $logger)
    {
        $logger->log($content);
    }
}



class DBLogger implements Logger
{
    public function log()
    {
        //log to db
    }
}

class FileLogger implements Logger
{
    public function log()
    {
        //log to file
    }
}

class CloudLogger implements Logger
{
    public function log()
    {
        //log to cloud
    }
}


class RedisLogger implements Logger
{
    public function log()
    {
        //log to redis
    }
}

<?php

return [
    'default' => env('LOG_TARGET', 'file'),

    'file' => [
        'class' => App\Log\FileLogger::class,
    ],

    'db' => [
        'class' => App\Log\DBLogger::class,
    ],

    'redis' => [
        'class' => App\Log\RedisLogger::class,
    ]
];



class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $default = config('log.default');
        $logger = config("log.{$default}.class");

        $this->app->bind(
            App\Contracts\Logger::class, // the logger interface
            $logger
        );
    }
}

?>
