<?php
declare(strict_types=1);

namespace Pangio\Core\Infrastructure;

use Pangio\Core\System\Config;
use RuntimeException;

/**
 * Provides a static logging utility that writes timestamped log entries with types to daily log files, using
 * configurable storage paths and basic file handling.
 *
 * @author Julius Derigs <julius.derigs@pangio.de>
 */
class Logger {
    /**
     * Holds the path to the logs storage directory.
     *
     * @var string|mixed
     */
    private static string $logsDir;

    ####################################################################################################################
    # --- PUBLIC METHODS --------------------------------------------------------------------------------------------- #
    ####################################################################################################################

    /**
     * Writes a log entry with type and timestamp to a daily log file, creating or appending to the file and
     * throwing an exception if the file cannot be opened.
     *
     * @param string $type
     * @param string $message
     * @return void
     */
    public static function log(string $type, string $message): void {
        self::$logsDir = $_ENV['APP_LOGS_DIR'] ?? Config::get('app.logsDir');

        $date = date('Ymd');
        $timestamp = date('d.m.Y - H:i:s');
        $path = dirname(__DIR__, 2) . '/' . self::$logsDir . "/$date.log";

        $handle = fopen($path, 'ab');

        if (!$handle) {
            throw new RuntimeException("Unable to open log file: $path");
        }

        fwrite($handle, "$timestamp - $type - $message" . PHP_EOL);
        fclose($handle);
    }
}
