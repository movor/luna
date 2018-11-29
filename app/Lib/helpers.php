<?php

/**
 * Seed pivot table
 *
 * @param string  $pivotTable           Pivot table name
 * @param string  $firstTable           First table name
 * @param string  $secondTable          Second table name
 * @param Closure $customColumnCallback Should return array of additional columns and their values to be inserted in
 *                                      pivot table
 * @param bool    $timestamps           Insert timestamps
 *
 * @return bool
 */
function seedPivotData($pivotTable, $firstTable, $secondTable, Closure $customColumnCallback = null, $timestamps = true)
{
    $firstIds = DB::table($firstTable)->inRandomOrder()->pluck('id')->toArray();
    $secondCount = DB::table($secondTable)->count();
    $data = [];

    foreach ($firstIds as $firstId) {
        $secondIds = [];
        for ($i = 0; $i <= rand(1, ceil($secondCount / 3)); $i++) {
            if (!$secondIds) {
                $secondIds = DB::table($secondTable)->inRandomOrder()->pluck('id')->toArray();
            }

            // TODO
            // Handle table that have no id column

            $row = [
                str_singular($firstTable) . '_id' => $firstId,
                str_singular($secondTable) . '_id' => ($secondId = array_pop($secondIds))
            ];

            // Add column => value from callback return array
            if (is_callable($customColumnCallback)) {
                $row = array_merge($row, $customColumnCallback($firstId, $secondId));
            }

            // Add timestamps
            if ($timestamps) {
                $now = \Carbon\Carbon::now();
                $row = array_merge($row, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $data[] = $row;
        }
    }

    return DB::table($pivotTable)->insert($data);
}

/**
 * Write to Laravel log.
 * Helper shortcut for laravel log method.
 *
 * @param        $var
 * @param string $level
 */
function ll($var, $level = 'debug')
{
    if (is_object($var)) {
        $var = (array) $var;
    } elseif (is_bool($var)) {
        $var = $var ? '~TRUE~' : '~FALSE~';
    } elseif (is_null($var)) {
        $var = '~NULL~';
    }

    \Log::$level($var);
}

/**
 * Chance to $win or $loose variables/callbacks
 *
 * @param int           $percent In case percent not between 0 - 100, return false
 * @param Closure|mixed $win     Callback to call or value to return if chances is fulfilled
 * @param mixed         $loose   Callback to call or value to return if chances is not fulfilled
 *
 * @return mixed
 */
function chance($percent = 50, $win = true, $loose = false)
{
    if (!is_numeric($percent)) {
        throw new \InvalidArgumentException('Percent needs to be a number or numeric string');
    }

    // Normalize percent
    if ($percent < 0) {
        $percent = 0;
    } elseif ($percent > 100) {
        $percent = 100;
    } else {
        $percent = (int) $percent;
    }

    return (rand(0, 100) <= $percent)
        // Win
        ? (is_callable($win) ? $win() : $win)
        // Loose
        : (is_callable($loose) ? $loose() : $loose);
}

/**
 * Storage path including prefix app dir
 *
 * @param string $path
 *
 * @return string
 */
function storage_path_app($path = '')
{
    // Make sure we do not have leading slash in path
    if (str_start($path, '/')) {
        $path = ltrim($path, '/');
    }

    return storage_path('app' . ($path ? '/' . $path : ''));
}

/**
 * Generate valid RFC 4211 compliant Universally Unique Identifiers (UUID)
 * version 3, 4 and 5.
 *
 * @return string
 */
function uuid()
{
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}