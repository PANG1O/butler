<?php
declare(strict_types=1);

namespace Pangio\Core\System;

/**
 * Provides security-related utility methods for hashing passwords and filtering input data based on allowed fields.
 *
 * @author Julius Derigs <julius.derigs@pangio.de>
 */
class Security {
    ####################################################################################################################
    # --- PUBLIC METHODS --------------------------------------------------------------------------------------------- #
    ####################################################################################################################

    /**
     * Hashes a password using a secure default algorithm and returns the resulting hash.
     *
     * @param string $password
     * @return string
     */
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Filters the input array to include only keys that are present in the allowed list.
     *
     * @param array $input
     * @param array $allowed
     * @return array
     */
    public static function filterAllowed(array $input, array $allowed): array {
        return array_intersect_key($input, array_flip($allowed));
    }
}