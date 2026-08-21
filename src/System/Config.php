<?php
declare(strict_types=1);

namespace Pangio\Core\System;

/**
 * A static configuration manager that loads PHP files from a directory and provides access to settings using dot notation.
 *
 * @author Julius Derigs <julius.derigs@pangio.de>
 */
class Config {
    /**
     * Internal cache of loaded configuration data.
     *
     * @var array
     */
    private static array $items = [];

    /**
     * Loads all configuration files from a given directory.
     *
     * @return void
     */
    public static function load(): void {
        $configDir = $_ENV['APP_CONFIG_DIR'] ?? 'config';
        $path = dirname(__DIR__, 2) . "/$configDir";

        if (!is_dir($path)) {
            return;
        }

        foreach (glob("$path/*.php") as $file) {
            $key = basename($file, '.php');
            $config = require $file;

            if (is_array($config)) {
                self::$items[$key] = $config;
            }
        }
    }

    /**
     * Retrieves a configuration value using dot notation.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed {
        $segments = explode('.', $key);
        $value = self::$items;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }

            $value = $value[$segment];
        }

        return $value;
    }

    /**
     * Sets a configuration value at runtime.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set(string $key, mixed $value): void {
        $segments = explode('.', $key);
        $temp = &self::$items;

        foreach ($segments as $segment) {
            if (!isset($temp[$segment]) || !is_array($temp[$segment])) {
                $temp[$segment] = [];
            }

            $temp = &$temp[$segment];
        }

        $temp = $value;
    }

    /**
     * Returns all loaded configuration data.
     *
     * @return array
     */
    public static function all(): array {
        return self::$items;
    }

    /**
     * Retrieves the application secret from environment variables or configuration, returning an empty string if not set.
     *
     * @return string
     */
    public static function getSecret(): string {
        return $_ENV['SECRET'] ?? self::get('app.secret') ?? '';
    }
}