<?php
spl_autoload_register('loadClass');

function loadClass($filename){
    $path = "";
    $extension = ".php";
    $fullPath = $path . $filename . $extension;
    $fullPath = str_replace('\\', '/', $fullPath);

    if(!file_exists($fullPath) )
    {
        return false;
    }
    include_once $fullPath;
}