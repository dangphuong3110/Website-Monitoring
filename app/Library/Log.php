<?php

/**
 * Log Class.
 *
 * Main class for campaign sending logging
 *
 * LICENSE: This product includes software developed at
 * the Zozo Co., Ltd. (http://zozo.vn/).
 *
 * @category   Zozo EMA
 *
 * @author     tieudv <tieudv@zozo.vn>
 * @copyright  Zozo Co., Ltd
 * @license    Zozo Co., Ltd
 *
 * @version    1.0
 *
 * @link       http://zozo.vn
 */

namespace App\Library;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

/**
 * Read configuration settings
 * Setup init CONSTANTS.
 */
class Log
{
    public static $logger;
    public static $path;

    /**
     * Debug.
     *
     * @return mixed
     */
    public static function debug($message)
    {
        self::$logger->debug($message);
    }

    /**
     * Info.
     *
     * @return mixed
     */
    public static function info($message)
    {
        self::$logger->info($message);
    }

    /**
     * Notice.
     *
     * @return mixed
     */
    public static function notice($message)
    {
        self::$logger->notice($message);
    }

    /**
     * Warning.
     *
     * @return mixed
     */
    public static function warning($message)
    {
//        self::$logger->warning($message);
        if (self::$logger) {
            self::$logger->warning($message);
        } else {
            // Log a fallback message if the logger is not available
            error_log('Logger not initialized: ' . $message);
        }
    }

    /**
     * Error.
     *
     * @return mixed
     */
    public static function error($message)
    {
        self::$logger->error($message);
    }

    /**
     * Critical.
     *
     * @return mixed
     */
    public static function critical($message)
    {
        self::$logger->critical($message);
    }

    /**
     * Alert.
     *
     * @return mixed
     */
    public static function alert($message)
    {
        self::$logger->alert($message);
    }

    /**
     * Emergency.
     *
     * @return mixed
     */
    public static function emergency($message)
    {
        self::$logger->emergency($message);
    }

    /**
     * Reconfigure log format for the forked process.
     *
     * @return mixed
     */
    public static function fork()
    {
        $pid = getmypid();
        $output = '[%datetime%] #'.$pid." %level_name%: %message%\n";
        $formatter = new LineFormatter($output);

        $stream = new StreamHandler(self::$path, Logger::INFO);
        $stream->setFormatter($formatter);

        self::$logger = new Logger('mailer');
        self::$logger->pushHandler($stream);
    }

    /**
     * Configure log format, used at initialization.
     *
     * @return logger
     */
    public static function configure($path)
    {
        $pid = getmypid();
        $output = '[%datetime%] #'.$pid." %level_name%: %message%\n";
        $formatter = new LineFormatter($output);

        $stream = new StreamHandler($path, Logger::INFO);
        $stream->setFormatter($formatter);

        self::$logger = new Logger('mailer');
        self::$logger->pushHandler($stream);
        self::$path = $path;
    }

    /**
     * Create a custom logger.
     *
     * @return logger
     */
    public static function create($path, $name = 'default')
    {
        $pid = getmypid();
        $output = '[%datetime%] #'.$pid." %level_name%: %message%\n";
        $formatter = new LineFormatter($output);

        $stream = new StreamHandler($path, Logger::INFO);
        $stream->setFormatter($formatter);

        $logger = new Logger($name);
        $logger->pushHandler($stream);

        return $logger;
    }
}
