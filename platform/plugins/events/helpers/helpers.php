<?php
use Botble\Events\Models\CategoryEvents;

if (!function_exists('getCategoryEventsName')) {

    function getCategoryEventsName($data)
    {
        $result = [];
        foreach ($data as $key => $item) {

            # code...
           $result[$item->id] = $item->name;
        }
        return $result;
    }
}


if (!function_exists('getCategoryEventsID')) {

    function getCategoryEventsID($data)
    {
        $result = [];
        foreach ($data as $key => $item) {
            # code...
            array_push($result,$item->id);
        }
        return $result;
    }
}

if (!function_exists('getCategoryContentKeyNameNotice')) {

    function getCategoryContentKeyNameNotice($data, $keyModel)
    {
        $result = [];
        foreach ($data as $key => $item) {

            # code...
            $result[$keyModel . '-'. $item->id] = $item->name;
        }
        return $result;
    }
}
