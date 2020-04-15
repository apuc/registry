<?php

use Carbon\Carbon;

if (!function_exists('format_money_value')) {
    function format_money_value($value)
    {
        if ($value === null) {
            return '';
        }
        return number_format($value, 2, ',', ' ');
    }
}


if (!function_exists('format_area_value')) {
    function format_area_value($value)
    {
        if ($value === null) {
            return '';
        }
        return number_format($value, 2, ',', ' ');
    }
}
