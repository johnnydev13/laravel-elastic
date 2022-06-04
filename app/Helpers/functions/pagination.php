<?php
if (!function_exists('limit')) {
    function limit()
    {
        return request('limit', 5);
    }
}
