<?php
error_reporting(E_ALL);

function loopObject($object, $fp){
    foreach ($object as $key => $value){
        if(is_array($value) || is_object($value)){
            loopObject($value, $fp);
        } else {
            fputcsv($fp, [$key => $value]);
        }
    }
}

$fp = fopen('file.csv', 'w');
foreach (new DirectoryIterator('files') as $fileInfo) {
    if ($fileInfo->isDot()) continue;
    echo $fileInfo->getFilename() . "<br>\n";

    $file = json_decode(file_get_contents('files/' . $fileInfo->getFilename()));

    if (isset($file->statuses)) {
        foreach ($file->statuses as $value) {
            foreach ($value as $key => $item) {

                if (!is_array($item) && !is_object($item)) {
                    fputcsv($fp, [$key => $item]);
                } else {
                    loopObject($item,$fp);
                }
            }
        }
    }
}

fclose($fp);