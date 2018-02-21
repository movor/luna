<?php

/**
 * Seed pivot table
 *
 * @param string $pivotTable Pivot table name
 * @param string $firstTable First table name
 * @param string $secondTable Second table name
 * @param Closure $customColumnCallback Should return array of additional columns and their values to be inserted in
 *                                      pivot table
 * @param bool $timestamps Insert timestamps
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
            if (!$secondIds) $secondIds = DB::table($secondTable)->inRandomOrder()->pluck('id')->toArray();

            // TODO
            // Handle table that have no id column

            $row = [
                str_singular($firstTable) . '_id' => $firstId,
                str_singular($secondTable) . '_id' => array_pop($secondIds)
            ];

            // Add column => value from callback return array
            if (is_callable($customColumnCallback)) $row = array_merge($row, $customColumnCallback());

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
    if (is_object($var)) $var = (array)$var;

    \Log::$level($var);
}

/**
 * Chance to return true
 *
 * @param int $percent In case percent not between 0 - 100, return false
 *
 * @return bool
 */
function chance($percent = 50)
{
    if (is_numeric($percent) && $percent >= 0 && $percent <= 100) {
        return !((bool)rand(0, (int)(100 / $percent - 1)));
    }

    return false;
}
