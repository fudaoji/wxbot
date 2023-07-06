<?php

if (!function_exists('abort')) {

    function abort($httpCode)
    {
        throw new Exception('Error', $httpCode);
    }
}

if (!function_exists('array_only')) {

    function array_only(array $array, $columns)
    {
        if (is_array($columns)) {
            $data = [];
            foreach ($columns as $column) {
                $data[$column] = $array[$column] ?: null;
            }
        } else {
            $data[$columns] = $array[$columns] ?: null;
        }
        return $data;
    }
}