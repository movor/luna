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
    if (is_object($var)) $var = (array) $var;

    \Log::$level($var);
}

/**
 * Chance to return true or to run callback function
 *
 * @param int      $percent  In case percent not between 0 - 100, return false
 * @param  Closure $callback Callback to call if chances is fulfilled fu
 * @param  mixed   $default  Default return value when using closure if chance is not fulfilled
 *
 * @return bool
 */
function chance($percent = 50, Closure $callback = null, $default = null)
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

    if ($percent == 0) {
        return false;
    }

    $win = rand(0, 100) <= $percent;

    if (!is_callable($callback)) {
        return $win;
    }

    if ($win) {
        return $callback();
    } else {
        return $default;
    }
}

/**
 * Get placeholder image (and cache it for further use) from picsum.photos service
 *
 * @param        $name
 * @param string $width  Width in pixels
 * @param string $height Height in pixels
 *
 * @return mixed
 */
function getPlaceholderImage($name, $width = '1280', $height = '720')
{
    return \Cache::rememberForever("placeholderImage.$name", function () use ($width, $height) {
        return file_get_contents("https://picsum.photos/$width/$height?random");
    });
}