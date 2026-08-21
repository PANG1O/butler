<?php
namespace App\Models;

use Pangio\Core\Infrastructure\Database;
use RuntimeException;

/**
 * Provides a base model abstraction with static methods for retrieving, finding, and inserting database records using
 * predefined table and field definitions.
 *
 * @author Julius Derigs <julius.derigs@pangio.de>
 */

class Base {
    protected static string $table;
    protected static array $fields;

    ####################################################################################################################
    # --- PUBLIC METHODS --------------------------------------------------------------------------------------------- #
    ####################################################################################################################

    /**
     * Retrieves all entries from the database table, filtered by where conditions.
     *
     * @param array $wheres
     * @return array
     */
    public static function all(array $wheres = []): array {
        try {
            $query = 'SELECT ' . implode(', ', static::$fields) . ' FROM ' . static::$table;

            if ($wheres) {
                $clauses = array_map(static fn($field) => "$field = :$field", array_keys($wheres));
                $query .= ' WHERE ' . implode(' AND ', $clauses);
            }

            return Database::select($query, $wheres);
        }
        catch (RuntimeException $exception) {
            throw new RuntimeException('[Base::all()] ' . $exception->getMessage());
        }
    }

    /**
     * Retrieves a single entry from the database entry, filtered by id.
     *
     * @param int $id
     * @return array
     */
    public static function find(int $id): array {
        try {
            $query = 'SELECT ' . implode(', ', static::$fields) . ' FROM ' . static::$table . ' WHERE id = :id';
            $result = Database::select($query, [ 'id' => $id ]);

            return $result[0] ?? [];
        }
        catch (RuntimeException $exception) {
            throw new RuntimeException('[Base::find()] ' . $exception->getMessage());
        }
    }

    /**
     * Inserts a new entry into the database table.
     *
     * @param array $params
     * @return bool
     */
    public static function insert(array $params): bool {
        try {
            $keys = array_keys($params);
            $fields = implode(', ', $keys);
            $placeholders = ':' . implode(', :', $keys);
            $query = 'INSERT INTO ' . static::$table . " ($fields) VALUES ($placeholders)";

            return Database::execute($query, $params);
        }
        catch (RuntimeException $exception) {
            throw new RuntimeException('[Base::insert()] ' . $exception->getMessage());
        }
    }

    /**
     * Updates a database record by ID using dynamically generated field assignments and parameterized queries.
     *
     * @param int $id
     * @param array $params
     * @return bool
     */
    public static function update(int $id, array $params): bool {
        try {
            $fields = array_map(
                static fn($key) => "$key = :$key",
                array_keys($params)
            );

            $set = implode(', ', $fields);

            $query = 'UPDATE ' . static::$table . " SET $set WHERE id = :id";

            $params['id'] = $id;

            return Database::execute($query, $params);
        }
        catch (RuntimeException $exception) {
            throw new RuntimeException('[Base::update()] ' . $exception->getMessage());
        }
    }
}