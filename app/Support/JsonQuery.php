<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

/**
 * Cross-database JSON field extraction helper.
 *
 * PostgreSQL uses the `->>` operator with a bare key name:  `column->>'key'`
 * SQLite (used in tests) uses the `json_extract` function:  `json_extract(column, '$.key')`
 *
 * Call `JsonQuery::extract('column', 'key')` to get the appropriate expression
 * for the currently active database driver.
 */
class JsonQuery
{
    /**
     * Return a SQL expression that extracts a text value from a JSON column.
     *
     * @param  string  $column  Unquoted column name (e.g. `slug`, `title`)
     * @param  string  $key  JSON key to extract (e.g. `en`, `fr`)
     */
    public static function extract(string $column, string $key): string
    {
        return match (DB::getDriverName()) {
            'pgsql' => "{$column}->>'$key'",
            default => "json_extract({$column}, '$.$key')",
        };
    }

    /**
     * Return a case-insensitive LIKE expression for a JSON text field.
     *
     * @param  string  $column  Unquoted column name
     * @param  string  $key  JSON key to extract
     */
    public static function ilike(string $column, string $key): string
    {
        $expr = self::extract($column, $key);

        return match (DB::getDriverName()) {
            'pgsql' => "lower({$expr})",
            default => "lower({$expr})",
        };
    }
}
