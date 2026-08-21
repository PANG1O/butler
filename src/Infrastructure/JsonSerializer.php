<?php
declare(strict_types=1);

namespace Pangio\Core\Infrastructure;

use RuntimeException;
use JsonException;

/**
 * Utility class for JSON encoding and decoding operations.
 *
 * @author Julius Derigs <julius.derigs@pangio.de>
 */
class JsonSerializer {
    ####################################################################################################################
    # --- PUBLIC METHODS --------------------------------------------------------------------------------------------- #
    ####################################################################################################################

    /**
     * Encodes an array or object into a JSON string.
     *
     * @param array|object $data
     * @return string
     */
    public static function encode(array|object $data): string {
        try {
            return json_encode($data, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }

    /**
     * Decodes a JSON string into an associative array.
     *
     * @param string $data
     * @return array
     */
    public static function decode(string $data): array {
        try {
            return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }
}