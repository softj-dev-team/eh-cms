<?php
if (!function_exists('table_actions_slides_contents')) {

    function table_actions_slides_contents($bulk_changes,$class,$url)
    {
        return view('plugins.contents::elements.tables.actions.actions',compact('bulk_changes', 'class','url'))->render();
    }
}
